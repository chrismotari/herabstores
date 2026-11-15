<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

/**
 * Adds a product to the cart. Adding a product that's already there
 * increases its quantity.
 */
class AddItemToCart
{
    public function __invoke(Cart $cart, Product $product, int $quantity = 1): CartItem
    {
        $item = $cart->cartItems()->firstOrNew(['product_id' => $product->id]);
        $newQuantity = ($item->exists ? $item->quantity : 0) + $quantity;

        EnsureAvailable::check($product, $newQuantity);

        $item->quantity = $newQuantity;
        $item->save();

        return $item;
    }
}
