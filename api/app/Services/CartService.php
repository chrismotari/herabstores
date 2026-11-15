<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function getGuestCart(): Cart
    {
        $cart = new Cart;
        $cartItems = session()->get('cart', []);
        $cartCollection = collect($cartItems)->map(function ($item, $productId) {
            return new CartItem([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
            ]);
        });

        $cart->setRelation('cartItems', $cartCollection);

        return $cart;
    }
}
