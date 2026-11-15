<?php

namespace App\Http\Resources\V1;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->cartItems;

        return [
            /** Guest carts only: send it back as the X-Cart-Token header. */
            'token' => $this->user_id ? null : $this->token,
            'items' => $items->map(fn ($item) => [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'line_total' => (float) ($item->product->price * $item->quantity),
                'product' => new ProductResource($item->product),
            ])->values(),
            'items_count' => (int) $items->sum('quantity'),
            /** Items total. Delivery is added at checkout (per zone). */
            'subtotal' => (float) $items->sum(fn ($item) => $item->product->price * $item->quantity),
        ];
    }
}
