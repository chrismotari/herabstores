<?php

namespace App\Http\Controllers;

use App\Models\Country;
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
            'countries' => Country::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd(auth()->user()->cart->cartItems);
        // dd($request->all());

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
        
        $cart_items = auth()->user()->cart->cartItems;

        // Check if the cart is empty
        if (empty($cart_items)) {
            flash()->error('Cart is empty');
            return redirect()->back();
        }

        // Calculate the total price of the cart items
        $totalPrice = array_reduce($cart_items->toArray(), function ($carry, $item) {
            return $carry + ($item->product->price * $item->quantity);
        }, 0);

        // Create a new order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => $totalPrice,
            'status' => "new",
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'shipping_method' => $request->shipping_method,
            // 'paid_at',
            'completed_at' => now(),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'company' => $request->company,
            'phone' => $request->phone,
            'alt_phone' => $request->alt_phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'estate_id' => $request->estate_id,
            'country_id' => $request->country_id,
            'message' => $request->message,
            'referee_code' => $request->referee_code,
            'cart_items' => $cart_items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name, // Assuming product relationship
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'discount' => 0,
                    'description' => "Description",
                ];
            })->toArray(),
        ]);

        // Add all cart items to the order as order items in json format
        // $order->items = json_encode($cartItems);
        // $order->save();

        // Clear the cart
        if ($order) {
            auth()->user()->cart->cartItems()->delete();
        }

        // Return the order
        return back()->with('success', 'Order has been placed successfully');
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
