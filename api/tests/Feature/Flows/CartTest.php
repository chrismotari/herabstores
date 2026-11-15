<?php

use App\Models\Product;
use App\Models\User;

test('guest can add a product to the session cart', function () {
    $product = Product::factory()->create();

    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 2])
        ->assertRedirect();

    expect(session('cart'))->toHaveKey($product->id)
        ->and(session('cart')[$product->id]['quantity'])->toEqual(2);
});

test('guest cannot add the same product twice', function () {
    $product = Product::factory()->create();
    $payload = ['product_id' => $product->id, 'quantity' => 1];

    $this->post(route('cart_items.store'), $payload);
    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 5]);

    expect(session('cart')[$product->id]['quantity'])->toBe(1);
});

test('guest can remove a product from the cart', function () {
    $product = Product::factory()->create();
    $this->withSession(['cart' => [$product->id => ['product_id' => $product->id, 'quantity' => 1]]]);

    $this->delete(route('cart_items.destroy', $product))->assertRedirect();

    expect(session('cart'))->not->toHaveKey($product->id);
});

test('guest can view a non-empty cart', function () {
    $product = Product::factory()->create();

    $this->withSession(['cart' => [$product->id => ['product_id' => $product->id, 'quantity' => 1]]])
        ->get(route('carts.show'))
        ->assertOk()
        ->assertSee($product->name);
});

test('adding to cart validates product and quantity', function () {
    $this->post(route('cart_items.store'), ['product_id' => 'nope', 'quantity' => 0])
        ->assertSessionHasErrors(['product_id', 'quantity']);
});

test('logged-in user gets a cart created on first add', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)
        ->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 3])
        ->assertRedirect();

    expect($user->fresh()->cart->cartItems)->toHaveCount(1)
        ->and($user->fresh()->cart->cartItems->first()->quantity)->toBe(3);
});

test('logged-in user can view and remove from their cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $this->actingAs($user)->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $this->get(route('carts.show'))->assertOk()->assertSee($product->name);

    $this->delete(route('cart_items.destroy', $product))->assertRedirect();

    expect($user->fresh()->cart->cartItems)->toBeEmpty();
});
