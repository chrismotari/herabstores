<?php

namespace App\Http\Resources\V1;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $subtotal = (float) $this->total;
        $fee = (float) $this->shipping_fee;

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            /** new, processing, in transit, completed, canceled, ... */
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'items' => collect($this->order_items)->map(fn ($item) => [
                'type' => $item['type'] ?? 'product',
                'product_id' => $item['product_id'] ?? null,
                'basket_id' => $item['basket_id'] ?? null,
                'name' => $item['name'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'line_total' => (float) $item['price'] * (int) $item['quantity'],
            ])->values(),
            'subtotal' => $subtotal,
            'discount' => (float) $this->discount,
            'discount_code' => $this->discount_code,
            'delivery_fee' => $fee,
            /** Amount due on delivery: subtotal - discount + delivery fee. */
            'total' => $subtotal - (float) $this->discount + $fee,
            'delivery' => [
                'fname' => $this->fname,
                'lname' => $this->lname,
                'phone' => $this->phone,
                'alt_phone' => $this->alt_phone,
                'email' => $this->email,
                'address' => $this->address,
                'city' => $this->city,
                'zone' => new DeliveryZoneResource($this->whenLoaded('deliveryZone')),
                'message' => $this->message,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'shipped_at' => $this->shipped_at?->toIso8601String(),
            'delivered_at' => $this->delivered_at?->toIso8601String(),
        ];
    }
}
