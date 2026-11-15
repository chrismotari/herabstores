<?php

namespace Database\Factories;

use App\Models\County;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<County>
 */
class CountyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'code' => fake()->unique()->numberBetween(1000, 99999), // 1-47 are Kenya's real counties (seeded by migration)
            'is_active' => true,
        ];
    }
}
