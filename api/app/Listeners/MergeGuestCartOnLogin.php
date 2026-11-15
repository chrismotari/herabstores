<?php

namespace App\Listeners;

use App\Actions\Cart\MergeGuestCart;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

/**
 * SPA (session) login: merge the session's guest cart into the user's cart.
 * (API logins merge explicitly using the X-Cart-Token header.)
 */
class MergeGuestCartOnLogin
{
    public function __construct(
        private Request $request,
        private CartService $carts,
        private MergeGuestCart $merge,
    ) {}

    public function handle(Login $event): void
    {
        if (! $this->request->hasSession()) {
            return;
        }

        $guest = $this->carts->guestCart($this->request->session()->pull(CartService::SESSION_KEY));

        if ($guest) {
            ($this->merge)($guest, $event->user);
        }
    }
}
