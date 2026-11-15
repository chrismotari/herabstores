<?php

namespace App\Http\Controllers;

use App\Actions\Cart\AddItemToCart;
use App\Actions\Cart\RemoveCartItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
    public function store(Request $request, CartService $carts, AddItemToCart $add)
    {
        $data = $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $add($carts->current($request, create: true), Product::findOrFail($data['product_id']), (int) $data['quantity']);
            flash()->success('Product added to cart');
        } catch (ValidationException $e) {
            flash()->error($e->validator->errors()->first());

            return back()->withErrors($e->validator);
        }

        return back();
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
    public function destroy(Request $request, Product $product, CartService $carts, RemoveCartItem $remove)
    {
        $remove($carts->current($request), $product);
        flash()->success('Product removed from cart');

        return back();
    }
}
