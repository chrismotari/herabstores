<?php

namespace App\Actions\Cart;

use App\Models\CartItem;

/**
 * Sets an item's quantity. Zero removes it.
 */
class UpdateCartItem
{
    public function __invoke(CartItem $item, int $quantity): ?CartItem
    {
        if ($quantity < 1) {
            $item->delete();

            return null;
        }

        EnsureAvailable::check($item->product, $quantity);
        $item->update(['quantity' => $quantity]);

        return $item;
    }
}
