<?php

namespace App\Actions\Cart;

use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Support\Facades\DB;

/**
 * At login, moves a guest cart's items into the user's cart, adding
 * quantities for products in both (capped at available stock). The
 * guest cart is deleted.
 */
class MergeGuestCart
{
    public function __construct(private CartService $carts) {}

    public function __invoke(Cart $guest, User $user): Cart
    {
        return DB::transaction(function () use ($guest, $user) {
            $cart = $this->carts->forUser($user, create: true);

            foreach ($guest->cartItems()->with('product')->get() as $item) {
                $existing = $cart->cartItems()->firstOrNew(['product_id' => $item->product_id]);
                $quantity = ($existing->exists ? $existing->quantity : 0) + $item->quantity;
                $existing->quantity = max(1, min($quantity, (int) $item->product->stock));
                $existing->save();
            }

            $guest->cartItems()->delete();
            $guest->delete();

            return $cart->load('cartItems');
        });
    }
}
