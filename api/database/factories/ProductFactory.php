<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraph(),
            'slug' => Str::slug($name),
            'price' => fake()->randomFloat(2, 50, 2000),
            'stock' => 50,
            'total_stock' => 100,
            'image_url' => fake()->imageUrl(),
            'is_active' => true,
            'category_id' => Category::factory(),
        ];
    }
}
