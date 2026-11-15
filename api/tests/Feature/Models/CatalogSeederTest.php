<?php

use App\Models\Product;
use Database\Seeders\CatalogSeeder;

test('the catalogue seeder can run repeatedly without duplicates', function () {
    $this->seed(CatalogSeeder::class);
    $count = Product::count();
    $this->seed(CatalogSeeder::class);

    expect($count)->toBeGreaterThan(50)
        ->and(Product::count())->toBe($count);
});

test('re-seeding does not overwrite stock or prices set by admins', function () {
    $this->seed(CatalogSeeder::class);
    $product = Product::firstWhere('slug', 'apples-3pcs');
    $product->update(['stock' => 7, 'price' => 999]);

    $this->seed(CatalogSeeder::class);

    expect($product->fresh()->stock)->toBe(7)
        ->and((float) $product->fresh()->price)->toBe(999.0);
});
