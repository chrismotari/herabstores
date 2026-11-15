<?php

namespace Database\Factories;

use App\Models\County;
use App\Models\Estate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Estate>
 */
class EstateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->streetName(),
            'is_active' => true,
            'county_id' => County::factory(),
        ];
    }
}
