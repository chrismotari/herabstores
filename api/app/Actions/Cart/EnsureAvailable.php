<?php

namespace App\Actions\Cart;

use App\Models\Product;
use Illuminate\Validation\ValidationException;

class EnsureAvailable
{
    public static function check(Product $product, int $quantity): void
    {
        if (! $product->is_active) {
            throw ValidationException::withMessages(['product_id' => 'This product is no longer available.']);
        }

        if ($quantity > $product->stock) {
            throw ValidationException::withMessages([
                'quantity' => $product->stock < 1
                    ? 'This product is out of stock.'
                    : "Only {$product->stock} left in stock.",
            ]);
        }
    }
}
