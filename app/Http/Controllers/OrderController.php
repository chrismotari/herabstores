<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
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
        return view('orders.create', [
            'cart' => Auth::user()->cart,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

        #   Take user's cart
        #   Check if the cart is empty
        #   get all cart items
        #   calculate the total price of the cart items
        #   create a new order
        #   add all cart items to the order as order items in json format
        #   clear the cart
        #   return the order
        #   
            // Take user's cart
        $cart = session()->get('cart', []);

        // Check if the cart is empty
        if (empty($cart)) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Get all cart items
        $cartItems = $cart['items'];

        // Calculate the total price of the cart items
        $totalPrice = array_reduce($cartItems, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        // Create a new order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
        ]);

        // Add all cart items to the order as order items in json format
        $order->items = json_encode($cartItems);
        $order->save();

        // Clear the cart
        session()->forget('cart');

        // Return the order
        return response()->json($order, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
