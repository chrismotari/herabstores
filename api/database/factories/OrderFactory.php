<?php

namespace Database\Factories;

use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'total' => fake()->randomFloat(2, 100, 5000),
            'shipping_fee' => 200,
            'order_items' => [],
            'status' => 'new',
            'payment_status' => 'pending',
            'payment_method' => 'cash_on_delivery',
            'fname' => fake()->firstName(),
            'lname' => fake()->lastName(),
            'phone' => fake()->numerify('07########'),
            'email' => fake()->safeEmail(),
            'address' => fake()->streetAddress(),
            'city' => 'Nairobi',
            'delivery_zone' => DeliveryZone::factory(),
        ];
    }
}
