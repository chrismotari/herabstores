<?php

namespace Database\Factories;

use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Promo '.fake()->unique()->word(),
            'code' => strtoupper(fake()->unique()->bothify('SAVE##??')),
            'discount_type' => 'percent',
            'value' => 10,
            'start_at' => now()->subDay(),
            'end_at' => now()->addMonth(),
            'is_active' => true,
        ];
    }
}
