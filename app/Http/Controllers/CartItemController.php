<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'product_id' => 'required|exists:products,id',
        //     'quantity' => 'required|integer|min:1',
        // ]);

        // $user = auth()->user();
        // $cart = $user->cart;

        // if (!$cart) {
        //     $cart = Cart::create([
        //         'user_id' => $user->id,
        //         'status' => 'open',
        //     ]);
        // }

        // $existingCartItem = CartItem::where('cart_id', $cart->id)
        //     ->where('product_id', $request->product_id)
        //     ->first();

        // if ($existingCartItem) {
        //     flash()->error('Product already in cart');
        //     return redirect()->back();
        // }
        // try {
        //     $user = auth()->user();
        //     $cart = $user->cart;
    
        //     if(!$cart) {
        //         $cart = Cart::create([
        //             'user_id' => $user->id,
        //             'status' => 'open',
        //         ]);
        //     }
    
        //     $cartItem = CartItem::create([
        //         'cart_id' => $cart->id,
        //         'product_id' => $request->product_id,
        //         'quantity' => $request->quantity,
        //     ]);
        //     flash()->success('Product added to cart');
        // } catch (\Throwable $th) {
        //     throw $th;
        //     // flash()->error('Failed to add product to cart');
        // }
        // return redirect()->back();


   
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (auth()->check()) {
            // Handle logged-in user
            $user = auth()->user();
            $cart = $user->cart;

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'status' => 'open',
                ]);
            }

            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingCartItem) {
                flash()->error('Product already in cart');
                return redirect()->back();
            }

            try {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                ]);

                flash()->success('Product added to cart');
            } catch (\Throwable $th) {
                flash()->error('Failed to add product to cart');
            }

        } else {
            // Handle guest user by storing in session
            $cart = session()->get('cart', new Cart());

            if (isset($cart[$request->product_id])) {
                flash()->error('Product already in cart');
                return redirect()->back();
            }

            $cart[$request->product_id] = [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ];

            session()->put('cart', $cart);
            flash()->success('Product added to cart');
        }

        dd($cart);
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $cartItem)
    {
        // dd($cartItem);
        $cartItem->delete();
        flash()->success('Product removed from cart');
        return redirect()->back();
    }
}
