<?php

namespace Database\Factories;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Courier>
 */
class CourierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'vehicle_type' => 'bike',
            'vehicle_number' => strtoupper(fake()->bothify('KM? ###?')),
            'is_active' => true,
        ];
    }
}
