<?php

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Testing\TestResponse;

function cartToken(TestResponse $response): string
{
    return $response->json('data.token');
}

test('a guest with no token sees an empty cart and nothing is created', function () {
    $this->getJson('/api/v1/cart')
        ->assertOk()
        ->assertJsonPath('data.items', [])
        ->assertJsonPath('data.token', null);

    expect(Cart::count())->toBe(0);
});

test('a guest gets a token on first add and keeps using it', function () {
    $tea = Product::factory()->create(['price' => 150, 'stock' => 10]);
    $flour = Product::factory()->create(['price' => 200, 'stock' => 10]);

    $token = cartToken($this->postJson('/api/v1/cart/items', ['product_id' => $tea->id, 'quantity' => 2])->assertOk());
    expect($token)->toBeString();

    $this->withHeader(CartService::HEADER, $token)
        ->postJson('/api/v1/cart/items', ['product_id' => $flour->id])
        ->assertOk()
        ->assertJsonPath('data.token', $token)
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.items_count', 3)
        ->assertJsonPath('data.subtotal', 500);
});

test('POST /cart creates an empty guest cart with a token', function () {
    $this->postJson('/api/v1/cart')->assertCreated()->assertJsonStructure(['data' => ['token']]);

    expect(Cart::whereNull('user_id')->count())->toBe(1);
});

test('a guest can update and remove items', function () {
    $product = Product::factory()->create(['stock' => 10]);
    $response = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id]);
    $token = cartToken($response);
    $itemId = $response->json('data.items.0.id');

    $this->withHeader(CartService::HEADER, $token)
        ->patchJson("/api/v1/cart/items/{$itemId}", ['quantity' => 4])
        ->assertOk()
        ->assertJsonPath('data.items.0.quantity', 4);

    $this->withHeader(CartService::HEADER, $token)
        ->deleteJson("/api/v1/cart/items/{$itemId}")
        ->assertOk()
        ->assertJsonPath('data.items', []);
});

test('setting quantity to zero removes the item', function () {
    $product = Product::factory()->create();
    $response = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id]);

    $this->withHeader(CartService::HEADER, cartToken($response))
        ->patchJson('/api/v1/cart/items/'.$response->json('data.items.0.id'), ['quantity' => 0])
        ->assertJsonPath('data.items', []);
});

test('stock and availability are enforced', function () {
    $low = Product::factory()->create(['stock' => 2]);
    $inactive = Product::factory()->create(['is_active' => false]);

    $this->postJson('/api/v1/cart/items', ['product_id' => $low->id, 'quantity' => 3])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['quantity' => 'Only 2 left in stock.']);

    $this->postJson('/api/v1/cart/items', ['product_id' => $inactive->id])
        ->assertJsonValidationErrors('product_id');
});

test('a cart item cannot be changed through someone else\'s cart', function () {
    $product = Product::factory()->create();
    $mine = $this->postJson('/api/v1/cart/items', ['product_id' => $product->id]);
    $theirs = $this->postJson('/api/v1/cart', [])->json('data.token');
    $itemId = $mine->json('data.items.0.id');

    $this->withHeader(CartService::HEADER, $theirs)
        ->patchJson("/api/v1/cart/items/{$itemId}", ['quantity' => 5])
        ->assertNotFound();
    $this->withHeaders([CartService::HEADER => ''])->deleteJson("/api/v1/cart/items/{$itemId}")->assertNotFound();
});

test('a logged-in user (bearer token) uses their own cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $token = $user->createToken('phone')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/cart/items', ['product_id' => $product->id])
        ->assertOk()
        ->assertJsonPath('data.token', null);

    expect($user->fresh()->cart->cartItems)->toHaveCount(1);

    $this->withToken($token)->getJson('/api/v1/cart')->assertJsonCount(1, 'data.items');
});

test('clearing empties the cart', function () {
    $product = Product::factory()->create();
    $token = cartToken($this->postJson('/api/v1/cart/items', ['product_id' => $product->id]));

    $this->withHeader(CartService::HEADER, $token)->deleteJson('/api/v1/cart')->assertJsonPath('data.items', []);
});

test('a malformed item id is a 404', function () {
    $this->patchJson('/api/v1/cart/items/not-a-uuid', ['quantity' => 1])->assertNotFound();
});
