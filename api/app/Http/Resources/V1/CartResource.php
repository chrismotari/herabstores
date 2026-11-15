<?php

namespace App\Http\Resources\V1;

use App\Models\Cart;
use App\Services\DiscountCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;

/** @mixin Cart */
class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->cartItems;
        $subtotal = (float) $items->sum(fn ($item) => $item->total);
        $discount = $this->discountFor($request, $items);

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
            'subtotal' => $subtotal,
            /** The applied discount code, or null. `error` explains why it no longer applies (amount is then 0). */
            'discount' => $discount,
            /** Subtotal minus discount (delivery not included). */
            'total' => round($subtotal - ($discount['amount'] ?? 0), 2),
        ];
    }

    /**
     * @return array{code: string, name: string|null, amount: float, error: string|null}|null
     */
    private function discountFor(Request $request, $items): ?array
    {
        if (! $this->discount_code) {
            return null;
        }

        try {
            $result = app(DiscountCalculator::class)->calculate($this->discount_code, $items, $request->user('sanctum'));

            return ['code' => $this->discount_code, 'name' => $result['offer']->name, 'amount' => $result['amount'], 'error' => null];
        } catch (ValidationException $e) {
            return ['code' => $this->discount_code, 'name' => null, 'amount' => 0.0, 'error' => $e->errors()[DiscountCalculator::FIELD][0] ?? 'Not valid.'];
        }
    }
}
