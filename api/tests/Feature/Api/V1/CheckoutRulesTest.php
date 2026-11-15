<?php

/*
 * Business rules for carts and checkout, ported from the old Blade storefront
 * tests (tests/Feature/Flows) when the Blade storefront was removed (spec 7.4).
 */

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Contact;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Str;

function rulesCheckout(array $overrides = []): array
{
    return array_merge([
        'fname' => 'Jane', 'lname' => 'Wanjiku', 'phone' => '0711000000', 'email' => 'jane@example.com',
        'address' => '12 Ngong Road', 'city' => 'Nairobi',
        'delivery_zone_id' => DeliveryZone::factory()->create(['delivery_fees' => 200])->id,
        'accept_terms' => true,
    ], $overrides);
}

/** A guest cart holding each product (quantity 2), returned as its token. */
function guestCartToken(Product ...$products): string
{
    $cart = Cart::create(['token' => (string) Str::uuid()]);
    foreach ($products as $product) {
        $cart->cartItems()->create(['product_id' => $product->id, 'quantity' => 2]);
    }

    return $cart->token;
}

function placeOrderAs(string $token, array $overrides = [])
{
    return test()->withHeader(CartService::HEADER, $token)->postJson('/api/v1/orders', rulesCheckout($overrides));
}

test('adding a product already in the cart increases its quantity', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $token = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 1])->json('data.token');

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 3])
        ->assertJsonPath('data.items.0.quantity', 4);
});

test('login merges carts, adding quantities and capping at stock', function () {
    $user = User::factory()->create();
    $shared = Product::factory()->create(['stock' => 3]);
    $guestOnly = Product::factory()->create(['stock' => 10]);
    $user->carts()->create(['status' => 'open'])->cartItems()->create(['product_id' => $shared->id, 'quantity' => 2]);
    $token = guestCartToken($shared, $guestOnly); // 2 of each

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'x'])
        ->assertOk();

    $items = $user->fresh()->cart->cartItems->keyBy('product_id');
    expect($items[$shared->id]->quantity)->toBe(3) // 2 + 2, capped at stock 3
        ->and($items[$guestOnly->id]->quantity)->toBe(2)
        ->and(Cart::whereNull('user_id')->count())->toBe(0);
});

test('delivery fee comes from the chosen zone', function () {
    $zone = DeliveryZone::factory()->create(['delivery_fees' => 350]);

    placeOrderAs(guestCartToken(Product::factory()->create()), ['delivery_zone_id' => $zone->id])->assertCreated();

    expect((float) Order::sole()->shipping_fee)->toBe(350.0);
});

test('inactive delivery zones are rejected', function () {
    $zone = DeliveryZone::factory()->create(['is_active' => false]);

    placeOrderAs(guestCartToken(Product::factory()->create()), ['delivery_zone_id' => $zone->id])
        ->assertJsonValidationErrorFor('delivery_zone_id');

    expect(Order::count())->toBe(0);
});

test('checkout requires delivery details and accepting terms', function (string $field) {
    placeOrderAs(guestCartToken(Product::factory()->create()), [$field => null])
        ->assertJsonValidationErrorFor($field);
})->with(['fname', 'lname', 'phone', 'address', 'city', 'delivery_zone_id', 'accept_terms']);

test('signed-in checkout saves the first address but never overwrites one', function () {
    $fresh = User::factory()->create(['address' => null, 'city' => null]);
    $existing = User::factory()->create(['address' => 'Old Address']);

    foreach ([$fresh, $existing] as $user) {
        $token = $user->createToken('phone')->plainTextToken;
        $this->withToken($token)->postJson('/api/v1/cart/items', ['product_id' => Product::factory()->create()->id]);
        $this->withToken($token)->postJson('/api/v1/orders', rulesCheckout())->assertCreated();
        $this->app['auth']->forgetGuards();
    }

    expect($fresh->fresh()->address)->toBe('12 Ngong Road')
        ->and($fresh->fresh()->city)->toBe('Nairobi')
        ->and($existing->fresh()->address)->toBe('Old Address');
});

test('marketing consent is recorded as a contact, with or without an email', function (?string $email) {
    placeOrderAs(guestCartToken(Product::factory()->create()), ['email' => $email, 'consented' => true])
        ->assertCreated();

    expect(Contact::sole()->phone)->toBe('0711000000');
})->with(['with email' => 'jane@example.com', 'without email' => null]);

test('checkout fails without changes when stock ran out after adding to cart', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $token = guestCartToken($product); // quantity 2
    $product->update(['stock' => 1]);

    placeOrderAs($token)->assertUnprocessable();

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(1)
        ->and(CartItem::count())->toBe(1);
});

test('checkout fails when a product was deactivated', function () {
    $product = Product::factory()->create();
    $token = guestCartToken($product);
    $product->update(['is_active' => false]);

    placeOrderAs($token)->assertUnprocessable();

    expect(Order::count())->toBe(0);
});

test('order prices are taken from the product at checkout time', function () {
    $product = Product::factory()->create(['price' => 100]);
    $token = guestCartToken($product);
    $product->update(['price' => 120]);

    placeOrderAs($token)->assertCreated()->assertJsonPath('data.subtotal', 240);
});
