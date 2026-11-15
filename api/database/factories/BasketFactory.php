<?php

namespace Database\Factories;

use App\Models\Basket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Basket>
 */
class BasketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = ucfirst(fake()->unique()->words(2, true)).' basket',
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 500, 5000),
            'stock' => 20,
            'is_active' => true,
            'effective_from' => now()->subDay(),
            'effective_to' => now()->addMonth(),
        ];
    }
}
