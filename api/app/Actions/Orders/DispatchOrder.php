<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Store;

class DispatchOrder
{
    public function __invoke(Order $order, Courier $courier, ?Store $store = null): Order
    {
        $order->transitionTo(OrderStatus::Dispatched);
        $order->update([
            'courier_id' => $courier->id,
            'store_id' => $store?->id,
            'shipped_at' => now(),
        ]);

        return $order;
    }
}
