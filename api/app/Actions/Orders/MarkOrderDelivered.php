<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Order;

/**
 * Pay on delivery: delivery is when the payment is collected.
 */
class MarkOrderDelivered
{
    public function __invoke(Order $order): Order
    {
        $order->transitionTo(OrderStatus::Delivered);
        $order->update([
            'delivered_at' => now(),
            'completed_at' => now(),
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return $order;
    }
}
