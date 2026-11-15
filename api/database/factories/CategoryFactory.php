<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = ucfirst(fake()->unique()->words(2, true)),
            'description' => fake()->sentence(),
            'slug' => Str::slug($name),
            'is_active' => true,
        ];
    }
}
