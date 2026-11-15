<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request, CartService $carts)
    {
        $cart = $carts->current($request);
        if ($cart->cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        return view('carts.show', compact('cart'));
    }
}
