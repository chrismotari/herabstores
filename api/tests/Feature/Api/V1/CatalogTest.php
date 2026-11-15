<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;

// Categories

test('lists active categories with available product counts', function () {
    $fruit = Category::factory()->create(['name' => 'Fruit']);
    Category::factory()->create(['name' => 'Hidden', 'is_active' => false]);
    Product::factory()->count(2)->for($fruit)->create();
    Product::factory()->for($fruit)->create(['is_active' => false]);

    $this->getJson('/api/v1/categories')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', $fruit->slug)
        ->assertJsonPath('data.0.products_count', 2);
});

test('shows a category by slug; inactive or unknown is 404', function () {
    $category = Category::factory()->create();
    $hidden = Category::factory()->create(['is_active' => false]);

    $this->getJson("/api/v1/categories/{$category->slug}")->assertOk()->assertJsonPath('data.id', $category->id);
    $this->getJson("/api/v1/categories/{$hidden->slug}")->assertNotFound();
    $this->getJson('/api/v1/categories/nope')->assertNotFound();
});

// Products: listing

test('lists only available products, paginated', function () {
    Product::factory()->count(3)->create();
    Product::factory()->create(['is_active' => false]);
    Product::factory()->for(Category::factory()->state(['is_active' => false]))->create();

    $this->getJson('/api/v1/products?per_page=2')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 3)
        ->assertJsonStructure(['data' => [['id', 'name', 'slug', 'price', 'in_stock', 'stock_status', 'image_url', 'category' => ['slug']]], 'links', 'meta']);
});

test('filters by search, category, price and stock', function () {
    $tea = Category::factory()->create(['slug' => 'tea']);
    Product::factory()->for($tea)->create(['name' => 'Kericho Gold', 'price' => 300, 'stock' => 5]);
    Product::factory()->for($tea)->create(['name' => 'Ketepa Pride', 'price' => 150, 'stock' => 0]);
    Product::factory()->create(['name' => 'Maize Flour', 'price' => 200, 'description' => 'Not a beverage']);

    $names = fn (string $query) => collect($this->getJson("/api/v1/products?{$query}")->assertOk()->json('data'))->pluck('name')->sort()->values()->all();

    expect($names('q=kericho'))->toBe(['Kericho Gold'])
        ->and($names('category=tea'))->toBe(['Kericho Gold', 'Ketepa Pride'])
        ->and($names('min_price=160&max_price=250'))->toBe(['Maize Flour'])
        ->and($names('category=tea&in_stock=1'))->toBe(['Kericho Gold']);
});

test('search treats % and _ literally', function () {
    Product::factory()->create(['name' => 'Plain product', 'description' => 'nothing special']);

    $this->getJson('/api/v1/products?q=%25')->assertOk()->assertJsonCount(0, 'data');
});

test('sorts by price', function () {
    Product::factory()->create(['price' => 300]);
    Product::factory()->create(['price' => 100]);
    Product::factory()->create(['price' => 200]);

    $prices = collect($this->getJson('/api/v1/products?sort=price_asc')->json('data'))->pluck('price')->all();
    expect($prices)->toEqual([100, 200, 300]);

    $this->getJson('/api/v1/products?sort=random')->assertJsonValidationErrors('sort');
});

test('listing does not run a query per product (no N+1)', function () {
    $count = function () {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->getJson('/api/v1/products')->assertOk();

        return count(DB::getQueryLog());
    };

    Product::factory()->count(2)->create();
    $few = $count();
    Product::factory()->count(10)->create();

    expect($count())->toBe($few);
});

// Products: detail

test('shows a product by slug with images and category', function () {
    $product = Product::factory()->create(['image_url' => 'products/main.jpg']);
    ProductImage::create(['product_id' => $product->id, 'image_url' => 'https://cdn.example/2.jpg', 'sort_order' => 2]);
    ProductImage::create(['product_id' => $product->id, 'image_url' => 'products/1.jpg', 'sort_order' => 1]);

    $this->getJson("/api/v1/products/{$product->slug}")
        ->assertOk()
        ->assertJsonPath('data.id', $product->id)
        ->assertJsonPath('data.image_url', asset('storage/products/main.jpg'))
        ->assertJsonPath('data.images', [asset('storage/products/1.jpg'), 'https://cdn.example/2.jpg'])
        ->assertJsonPath('data.category.id', $product->category_id);
});

test('inactive or unknown products are 404', function () {
    $hidden = Product::factory()->create(['is_active' => false]);

    $this->getJson("/api/v1/products/{$hidden->slug}")->assertNotFound();
    $this->getJson('/api/v1/products/nope')->assertNotFound();
});

test('exposes stock status and the orderable quantity', function () {
    $product = Product::factory()->create(['stock' => 3]);

    $this->getJson("/api/v1/products/{$product->slug}")
        ->assertJsonPath('data.in_stock', true)
        ->assertJsonPath('data.stock_status', 'Few Items Left')
        ->assertJsonPath('data.available_quantity', 3);
});

// Caching

test('catalog responses are publicly cacheable', function () {
    $product = Product::factory()->create();

    foreach (['/api/v1/categories', '/api/v1/products', "/api/v1/products/{$product->slug}"] as $url) {
        $cache = $this->getJson($url)->headers->get('Cache-Control');
        expect($cache)->toContain('public')->toContain('max-age=60')->toContain('s-maxage=300');
    }
});
