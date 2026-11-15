<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\Product;

class RemoveCartItem
{
    public function __invoke(Cart $cart, Product $product): void
    {
        if ($cart->exists) {
            $cart->cartItems()->where('product_id', $product->id)->delete();
        }
    }
}
