<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Models\Basket;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Cancels an undelivered order and puts its stock back.
 */
class CancelOrder
{
    public function __invoke(Order $order, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $reason) {
            $order = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $order->transitionTo(OrderStatus::Canceled);

            foreach ($order->order_items as $item) {
                $model = ($item['type'] ?? 'product') === 'basket'
                    ? Basket::find($item['basket_id'] ?? null)
                    : Product::find($item['product_id'] ?? null);
                $model?->increment('stock', (int) $item['quantity']);
            }

            $order->update([
                'canceled_at' => now(),
                'payment_status' => 'canceled',
                'message' => trim(($order->message ? $order->message."\n" : '').($reason ? 'Canceled: '.$reason : '')) ?: $order->message,
            ]);

            return $order;
        });
    }
}
