<?php

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;

test('lists only active baskets within their availability window', function () {
    $current = Basket::factory()->create();
    Basket::factory()->create(['is_active' => false]);
    Basket::factory()->create(['effective_from' => now()->addWeek(), 'effective_to' => now()->addMonth()]);
    Basket::factory()->create(['effective_from' => now()->subMonth(), 'effective_to' => now()->subDay()]);

    $this->getJson('/api/v1/baskets')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $current->id)
        ->assertJsonMissingPath('data.0.items');
});

test('shows a basket with its active items', function () {
    $basket = Basket::factory()->create();
    $tea = Product::factory()->create(['name' => 'Tea']);
    BasketProduct::factory()->create(['basket_id' => $basket->id, 'product_id' => $tea->id, 'quantity' => 2]);
    BasketProduct::factory()->create(['basket_id' => $basket->id, 'is_active' => false]);

    $this->getJson("/api/v1/baskets/{$basket->slug}")
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.quantity', 2)
        ->assertJsonPath('data.items.0.product.name', 'Tea');
});

test('unavailable baskets are 404', function () {
    $expired = Basket::factory()->create(['effective_to' => now()->subDay(), 'effective_from' => now()->subMonth()]);

    $this->getJson("/api/v1/baskets/{$expired->slug}")->assertNotFound();
});
