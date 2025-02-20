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
        return view('orders.index', [
            'orders' => Order::all(),
        ]);
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
        // dd($request->all());
        $cart_items = auth()->user()->cart->cartItems;

        if ($cart_items->count() < 1 ) {
            flash()->error('Cart is empty');
            return redirect()->back();
        }else{

            $totalPrice = auth()->user()->cart->total;
            //delivery fees here
    
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $totalPrice,
                'status' => "new",
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                // 'paid_at',
                'completed_at' => now(),
                'fname' => $request->fname,
                'lname' => $request->lname,
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
                'order_items' => $cart_items->map(function ($item) {
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
    
            // Clear the cart
            if ($order) {
                auth()->user()->cart->cartItems()->delete();
                return back()->with('success', 'Order has been placed successfully');
            }else{
                return back()->with('error', 'An Error occured while placing your order');
            }
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('orders.show', [
            'order' => $order,
        ]);
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
