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
                /** "product" or "basket"; the matching key below is set. */
                'type' => $item->type,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->total,
                'product' => $item->product_id ? new ProductResource($item->product) : null,
                'basket' => $item->basket_id ? new BasketResource($item->basket) : null,
            ])->values(),
            'items_count' => (int) $items->sum('quantity'),
            /** Items total. Delivery is added at checkout (per zone). */
            'subtotal' => (float) $items->sum(fn ($item) => $item->total),
        ];
    }
}
