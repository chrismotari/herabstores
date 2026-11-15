<?php

use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;

function apiCheckout(array $overrides = []): array
{
    return array_merge([
        'fname' => 'Jane', 'lname' => 'Wanjiku', 'phone' => '0711000000', 'email' => 'jane@example.com',
        'address' => '12 Ngong Road', 'city' => 'Nairobi',
        'delivery_zone_id' => DeliveryZone::factory()->create(['delivery_fees' => 200])->id,
        'accept_terms' => true,
    ], $overrides);
}

test('lists active delivery zones with fees', function () {
    DeliveryZone::factory()->create(['name' => 'Westlands', 'delivery_fees' => 250]);
    DeliveryZone::factory()->create(['name' => 'Closed', 'is_active' => false]);

    $this->getJson('/api/v1/delivery-zones')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Westlands')
        ->assertJsonPath('data.0.delivery_fee', 250);
});

test('a guest can check out with their cart token', function () {
    $product = Product::factory()->create(['price' => 150, 'stock' => 5]);
    $token = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 2])->json('data.token');

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/orders', apiCheckout())
        ->assertCreated()
        ->assertJsonPath('data.status', 'new')
        ->assertJsonPath('data.payment_method', 'cash_on_delivery')
        ->assertJsonPath('data.subtotal', 300)
        ->assertJsonPath('data.delivery_fee', 200)
        ->assertJsonPath('data.total', 500)
        ->assertJsonPath('data.items.0.quantity', 2)
        ->assertJsonPath('data.delivery.zone.delivery_fee', 200);

    expect($product->fresh()->stock)->toBe(3)
        ->and(Order::sole()->user_id)->toBeNull();

    // The guest cart is gone after checkout
    $this->withHeader(CartService::HEADER, $token)->getJson('/api/v1/cart')->assertJsonPath('data.items', []);
});

test('a logged-in user checks out their own cart', function () {
    $user = User::factory()->create();
    $token = $user->createToken('phone')->plainTextToken;
    $product = Product::factory()->create(['stock' => 5]);
    $this->withToken($token)->postJson('/api/v1/cart/items', ['product_id' => $product->id]);

    $this->withToken($token)->postJson('/api/v1/orders', apiCheckout())->assertCreated();

    expect(Order::sole()->user_id)->toBe($user->id);
});

test('checking out an empty cart is a 422', function () {
    $this->postJson('/api/v1/orders', apiCheckout())
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cart');
});

test('stock problems are reported per product', function () {
    $product = Product::factory()->create(['name' => 'Tea', 'stock' => 5]);
    $token = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'quantity' => 3])->json('data.token');
    $product->update(['stock' => 1]);

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/orders', apiCheckout())
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['cart' => 'Only 1 of Tea left.']);
});

test('checkout validates delivery details', function () {
    $this->postJson('/api/v1/orders', apiCheckout(['phone' => null, 'accept_terms' => false]))
        ->assertJsonValidationErrors(['phone', 'accept_terms']);
});
