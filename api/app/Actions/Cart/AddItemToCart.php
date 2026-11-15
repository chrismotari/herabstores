<?php

namespace App\Actions\Cart;

use App\Models\Basket;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

/**
 * Adds a product or basket to the cart. Adding one that's already there
 * increases its quantity.
 */
class AddItemToCart
{
    public function __invoke(Cart $cart, Product|Basket $item, int $quantity = 1): CartItem
    {
        $key = $item instanceof Basket ? 'basket_id' : 'product_id';
        $line = $cart->cartItems()->firstOrNew([$key => $item->id]);
        $newQuantity = ($line->exists ? $line->quantity : 0) + $quantity;

        EnsureAvailable::check($item, $newQuantity);

        $line->quantity = $newQuantity;
        $line->save();

        return $line;
    }
}
