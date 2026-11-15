<?php

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Contact;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

function checkoutPayload(array $overrides = []): array
{
    return array_merge([
        'fname' => 'Jane',
        'lname' => 'Wanjiku',
        'phone' => '0711000000',
        'email' => 'jane@example.com',
        'address' => '12 Ngong Road',
        'city' => 'Nairobi',
        'delivery_zone_id' => DeliveryZone::factory()->create(['delivery_fees' => 200])->id,
        'accept_terms' => 'on',
    ], $overrides);
}

function guestCartWith(Product ...$products): array
{
    return guestSessionWith($products, quantity: 2);
}

test('checkout page renders for a guest with a cart', function () {
    $product = Product::factory()->create();

    $this->withSession(guestCartWith($product))
        ->get(route('orders.create'))
        ->assertOk();
});

test('guest can place a pay-on-delivery order', function () {
    $product = Product::factory()->create(['price' => 150]);

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('shop'));

    $order = Order::sole();
    expect($order->user_id)->toBeNull()
        ->and((float) $order->total)->toBe(300.0)
        ->and((float) $order->shipping_fee)->toBe(200.0)
        ->and($order->status)->toBe(OrderStatus::New)
        ->and($order->payment_method)->toBe('cash_on_delivery')
        ->and($order->fname)->toBe('Jane')
        ->and($order->order_items[0]['price'])->toEqual(150)
        ->and($order->order_items[0]['quantity'])->toBe(2)
        ->and(Cart::count())->toBe(0);
});

test('delivery fee comes from the chosen zone', function () {
    $product = Product::factory()->create();
    $zone = DeliveryZone::factory()->create(['delivery_fees' => 350]);

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload(['delivery_zone_id' => $zone->id]));

    expect((float) Order::sole()->shipping_fee)->toBe(350.0);
});

test('inactive delivery zones are rejected', function () {
    $product = Product::factory()->create();
    $zone = DeliveryZone::factory()->create(['is_active' => false]);

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload(['delivery_zone_id' => $zone->id]))
        ->assertSessionHasErrors('delivery_zone_id');

    expect(Order::count())->toBe(0);
});

test('checkout page lists only active zones with their fee', function () {
    $product = Product::factory()->create();
    DeliveryZone::factory()->create(['name' => 'Westlands', 'delivery_fees' => 250]);
    DeliveryZone::factory()->create(['name' => 'Closed Zone', 'is_active' => false]);

    $this->withSession(guestCartWith($product))
        ->get(route('orders.create'))
        ->assertSee('Westlands')
        ->assertSee('data-fee="250.00"', false)
        ->assertDontSee('Closed Zone')
        ->assertDontSee('name="estate_id"', false);
});

test('logged-in checkout empties the cart and saves the first address', function () {
    $user = User::factory()->create(['address' => null, 'city' => null]);
    $product = Product::factory()->create();
    $this->actingAs($user)->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $this->post(route('orders.store'), checkoutPayload())->assertRedirect(route('shop'));

    $user->refresh();
    expect(Order::sole()->user_id)->toBe($user->id)
        ->and($user->cart->cartItems)->toBeEmpty()
        ->and($user->address)->toBe('12 Ngong Road')
        ->and($user->city)->toBe('Nairobi');
});

test('checkout does not overwrite an existing address', function () {
    $user = User::factory()->create(['address' => 'Old Address']);
    $product = Product::factory()->create();
    $this->actingAs($user)->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $this->post(route('orders.store'), checkoutPayload());

    expect($user->fresh()->address)->toBe('Old Address');
});

test('checkout with an empty cart creates no order', function () {
    $this->post(route('orders.store'), checkoutPayload())->assertRedirect();

    expect(Order::count())->toBe(0);
});

test('checkout requires delivery details and accepting terms', function (string $field) {
    $product = Product::factory()->create();

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload([$field => null]))
        ->assertSessionHasErrors($field);
})->with(['fname', 'lname', 'phone', 'address', 'city', 'delivery_zone_id', 'accept_terms']);

test('marketing consent is recorded as a contact', function () {
    $product = Product::factory()->create();

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload(['consented' => 'on']));

    expect(Contact::where('phone', '0711000000')->exists())->toBeTrue();
});

test('placing an order decrements stock', function () {
    $product = Product::factory()->create(['stock' => 5]);

    $this->withSession(guestCartWith($product))->post(route('orders.store'), checkoutPayload());

    expect($product->fresh()->stock)->toBe(3);
});

test('checkout fails without changes when stock ran out after adding to cart', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $session = guestCartWith($product); // quantity 2
    $product->update(['stock' => 1]);

    $this->withSession($session)
        ->post(route('orders.store'), checkoutPayload())
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(1)
        ->and(CartItem::count())->toBe(1);
});

test('checkout fails when a product was deactivated', function () {
    $product = Product::factory()->create();
    $session = guestCartWith($product);
    $product->update(['is_active' => false]);

    $this->withSession($session)
        ->post(route('orders.store'), checkoutPayload())
        ->assertSessionHasErrors('cart');

    expect(Order::count())->toBe(0);
});

test('order prices are taken from the product at checkout time', function () {
    $product = Product::factory()->create(['price' => 100]);
    $session = guestCartWith($product);
    $product->update(['price' => 120]);

    $this->withSession($session)->post(route('orders.store'), checkoutPayload());

    expect((float) Order::sole()->total)->toBe(240.0);
});

test('marketing consent works for customers who gave no email', function () {
    $product = Product::factory()->create();

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload(['email' => null, 'consented' => 'on']))
        ->assertSessionHasNoErrors();

    expect(Order::count())->toBe(1)
        ->and(Contact::sole()->phone)->toBe('0711000000');
});
