<?php

namespace App\Actions\Cart;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Validation\ValidationException;

class EnsureAvailable
{
    public static function check(Product|Basket $item, int $quantity): void
    {
        $field = $item instanceof Basket ? 'basket_id' : 'product_id';

        if (! static::isAvailable($item)) {
            throw ValidationException::withMessages([$field => 'This item is no longer available.']);
        }

        if ($quantity > $item->stock) {
            throw ValidationException::withMessages([
                'quantity' => $item->stock < 1 ? 'This item is out of stock.' : "Only {$item->stock} left in stock.",
            ]);
        }
    }

    public static function isAvailable(Product|Basket $item): bool
    {
        if ($item instanceof Basket) {
            return $item->is_active
                && (! $item->effective_from || $item->effective_from->lte(now()))
                && (! $item->effective_to || $item->effective_to->gte(now()));
        }

        return (bool) $item->is_active;
    }
}
