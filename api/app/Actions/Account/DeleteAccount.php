<?php

namespace App\Actions\Account;

use App\Models\Cart;
use App\Models\Courier;
use App\Models\Favourite;
use App\Models\Order;
use App\Models\Product;
use App\Models\Ref;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Deletes a customer account (required for app store listings).
 *
 * Orders are kept for the business's records but unlinked from the user
 * (they still hold the delivery details given at checkout). Favourites,
 * carts and API tokens are removed.
 */
class DeleteAccount
{
    public function __invoke(User $user): void
    {
        if (Courier::where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages(['account' => 'Courier accounts must be removed by an administrator.']);
        }

        DB::transaction(function () use ($user) {
            Order::where('user_id', $user->id)->update(['user_id' => null]);
            Ref::where('user_id', $user->id)->update(['user_id' => null]);
            Product::where('user_id', $user->id)->update(['user_id' => null]);
            Favourite::where('user_id', $user->id)->delete();

            Cart::where('user_id', $user->id)->each(function (Cart $cart) {
                $cart->cartItems()->delete();
                $cart->delete();
            });

            $user->tokens()->delete();
            $user->roles()->detach();
            $user->delete();
        });
    }
}
