<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;

test('home and shop pages render for guests', function (string $url) {
    Product::factory()->count(2)->create();

    $this->get($url)->assertOk();
})->with(['/', '/shop', '/about-us', '/contact-us', '/terms-and-conditions']);

test('home page renders for a logged-in user without a cart', function () {
    $this->actingAs(User::factory()->create())->get('/')->assertOk();
});

test('product page renders', function () {
    $product = Product::factory()->create();

    $this->get(route('products.show', $product))->assertOk()->assertSee($product->name);
});

test('search finds products by name and category', function () {
    $tea = Product::factory()->create(['name' => 'Kericho Gold Tea']);
    $other = Product::factory()->for(Category::factory())->create(['name' => 'Maize Flour']);

    $this->get('/products/search?search=Kericho')
        ->assertOk()
        ->assertSee('Kericho Gold Tea')
        ->assertDontSee('Maize Flour');

    $this->get('/products/search?category_id='.$other->category_id)
        ->assertOk()
        ->assertSee('Maize Flour')
        ->assertDontSee('Kericho Gold Tea');
});
