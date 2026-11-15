<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Finds the cart for the current request.
 *
 * - Logged-in user: their cart.
 * - Guest: a database cart identified by a token, read from the
 *   X-Cart-Token header, or the Sanctum session (Next.js SPA requests).
 *
 * Without $create, a guest with no cart gets an unsaved empty Cart so
 * page views don't create rows.
 */
class CartService
{
    public const HEADER = 'X-Cart-Token';

    public const SESSION_KEY = 'cart_token';

    public function current(Request $request, bool $create = false): Cart
    {
        // The sanctum guard covers web sessions and bearer tokens (cart routes are public).
        if ($user = $request->user('sanctum') ?? $request->user()) {
            return $this->forUser($user, $create);
        }

        $cart = $this->guestCart($this->token($request));

        if (! $cart && $create) {
            $cart = Cart::create(['token' => (string) Str::uuid(), 'status' => 'open']);
            if ($request->hasSession()) {
                $request->session()->put(self::SESSION_KEY, $cart->token);
            }
        }

        return $cart ?? $this->emptyCart();
    }

    public function forUser(User $user, bool $create = false): Cart
    {
        $cart = $user->carts()->first();

        if (! $cart && $create) {
            $cart = $user->carts()->create(['status' => 'open']);
        }

        return $cart ?? $this->emptyCart();
    }

    public function token(Request $request): ?string
    {
        return $request->header(self::HEADER)
            ?: ($request->hasSession() ? $request->session()->get(self::SESSION_KEY) : null);
    }

    public function guestCart(?string $token): ?Cart
    {
        return $token && Str::isUuid($token)
            ? Cart::whereNull('user_id')->where('token', $token)->first()
            : null;
    }

    private function emptyCart(): Cart
    {
        return (new Cart)->setRelation('cartItems', new Collection);
    }
}
