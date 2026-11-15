<?php

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Cart;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\QueryException;
use Illuminate\Testing\TestResponse;

function addBasket(Basket $basket, int $quantity = 1, ?string $token = null): TestResponse
{
    return test()->withHeaders($token ? [CartService::HEADER => $token] : [])
        ->postJson('/api/v1/cart/items', ['basket_id' => $basket->id, 'quantity' => $quantity]);
}

test('a basket is added as one line at its own price', function () {
    $basket = Basket::factory()->create(['price' => 1500, 'stock' => 5]);

    addBasket($basket, 2)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'basket')
        ->assertJsonPath('data.items.0.basket.id', $basket->id)
        ->assertJsonPath('data.items.0.product', null)
        ->assertJsonPath('data.items.0.line_total', 3000)
        ->assertJsonPath('data.subtotal', 3000);
});

test('products and baskets can share a cart; adding a basket again increases its quantity', function () {
    $basket = Basket::factory()->create(['price' => 1000, 'stock' => 5]);
    $product = Product::factory()->create(['price' => 100]);

    $token = addBasket($basket)->json('data.token');
    $this->withHeader(CartService::HEADER, $token)->postJson('/api/v1/cart/items', ['product_id' => $product->id]);
    addBasket($basket, 1, $token)
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.subtotal', 2100);
});

test('send exactly one of product_id or basket_id', function () {
    $basket = Basket::factory()->create();
    $product = Product::factory()->create();

    $this->postJson('/api/v1/cart/items', [])->assertJsonValidationErrors(['product_id', 'basket_id']);
    $this->postJson('/api/v1/cart/items', ['product_id' => $product->id, 'basket_id' => $basket->id])
        ->assertJsonValidationErrors('product_id');
});

test('unavailable or out-of-stock baskets cannot be added', function () {
    $expired = Basket::factory()->create(['effective_from' => now()->subMonth(), 'effective_to' => now()->subDay()]);
    $inactive = Basket::factory()->create(['is_active' => false]);
    $low = Basket::factory()->create(['stock' => 1]);

    addBasket($expired)->assertJsonValidationErrors('basket_id');
    addBasket($inactive)->assertJsonValidationErrors('basket_id');
    addBasket($low, 2)->assertJsonValidationErrors(['quantity' => 'Only 1 left in stock.']);
});

test('checking out a basket records it and decrements basket stock only', function () {
    $tea = Product::factory()->create(['stock' => 10]);
    $basket = Basket::factory()->create(['name' => 'Tea Lovers', 'price' => 1200, 'stock' => 3]);
    BasketProduct::factory()->create(['basket_id' => $basket->id, 'product_id' => $tea->id, 'quantity' => 2]);

    $token = addBasket($basket, 2)->json('data.token');

    $this->withHeader(CartService::HEADER, $token)->postJson('/api/v1/orders', [
        'fname' => 'Jane', 'lname' => 'W', 'phone' => '0711000000', 'address' => '1 Rd', 'city' => 'Nairobi',
        'delivery_zone_id' => DeliveryZone::factory()->create(['delivery_fees' => 100])->id, 'accept_terms' => true,
    ])
        ->assertCreated()
        ->assertJsonPath('data.items.0.type', 'basket')
        ->assertJsonPath('data.items.0.basket_id', $basket->id)
        ->assertJsonPath('data.items.0.name', 'Tea Lovers')
        ->assertJsonPath('data.subtotal', 2400)
        ->assertJsonPath('data.total', 2500);

    expect($basket->fresh()->stock)->toBe(1)
        ->and($tea->fresh()->stock)->toBe(10)
        ->and(Order::sole()->order_items[0]['product_id'])->toBeNull();
});

test('basket lines merge into the user cart at login', function () {
    $user = User::factory()->create();
    $basket = Basket::factory()->create(['stock' => 5]);
    $token = addBasket($basket, 2)->json('data.token');

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'password', 'device_name' => 'x'])
        ->assertOk();

    expect($user->fresh()->cart->cartItems->sole()->basket_id)->toBe($basket->id);
});

test('the database rejects a cart line with both or neither kind', function () {
    $cart = Cart::create(['token' => (string) Str::uuid()]);

    expect(fn () => $cart->cartItems()->create(['quantity' => 1]))
        ->toThrow(QueryException::class);
});
