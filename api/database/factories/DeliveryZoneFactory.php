<?php

namespace Database\Factories;

use App\Models\DeliveryZone;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<DeliveryZone>
 */
class DeliveryZoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = fake()->unique()->streetName().' zone',
            'slug' => Str::slug($name),
            'delivery_fees' => fake()->randomElement([100, 200, 300]),
            'is_active' => true,
        ];
    }
}
