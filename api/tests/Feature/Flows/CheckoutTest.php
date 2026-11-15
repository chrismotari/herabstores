<?php

use App\Models\Contact;
use App\Models\DeliveryZone;
use App\Models\Estate;
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
    return ['cart' => collect($products)->mapWithKeys(fn ($p) => [
        $p->id => ['product_id' => $p->id, 'quantity' => 2],
    ])->all()];
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
        ->and($order->status)->toBe('new')
        ->and($order->payment_method)->toBe('cash_on_delivery')
        ->and($order->fname)->toBe('Jane')
        ->and($order->order_items[0]['price'])->toEqual(150)
        ->and($order->order_items[0]['quantity'])->toBe(2)
        ->and(session('cart'))->toBeNull();
});

test('estate delivery fee takes precedence over the zone fee', function () {
    $product = Product::factory()->create();
    $estate = Estate::factory()->create(['delivery_fees' => 350]);

    $this->withSession(guestCartWith($product))
        ->post(route('orders.store'), checkoutPayload(['estate_id' => $estate->id]));

    expect((float) Order::sole()->shipping_fee)->toBe(350.0);
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
