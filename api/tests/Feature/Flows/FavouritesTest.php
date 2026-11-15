<?php

use App\Models\Product;
use App\Models\User;

test('logged-in user can favourite a product once', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('favourites.store'), ['product_id' => $product->id]);
    $this->actingAs($user)->post(route('favourites.store'), ['product_id' => $product->id]);

    expect($user->favourites()->count())->toBe(1);
});

test('guests cannot favourite', function () {
    $product = Product::factory()->create();

    $this->post(route('favourites.store'), ['product_id' => $product->id])
        ->assertRedirect(route('login'));
});
