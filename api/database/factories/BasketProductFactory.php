<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\BasketProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BasketProduct>
 */
class BasketProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'basket_id' => Basket::factory(),
            'product_id' => Product::factory(),
            'quantity' => fake()->numberBetween(1, 3),
            'is_active' => true,
        ];
    }
}
