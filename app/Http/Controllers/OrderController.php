<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Estate;
use App\Models\Order;
use App\Models\Ref;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin', except: ['store', 'update', 'track']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->query('status') === 'new') {
            $query->where('status', 'new'); 
        }

        $orders = $query->get();

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cartService = new CartService();
        return view('orders.create', [
            'selected_user' => Auth::user(),
            'cart' =>  auth()->check() ? auth()->user()->cart : $cartService->getGuestCart(),
            'estates' => Estate::all(),
            'refs' => Ref::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        // dd($request);
        $cartService = new CartService();
        $cart = auth()->check() ? auth()->user()->cart : $cartService->getGuestCart();
        $cart_items = $cart->cartItems;

        if ($cart_items->count() < 1 ) {
            flash()->error('Cart is empty');
            return redirect()->back();
        }else{

            $totalPrice = $cart->total;
            //delivery fees here
    
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $totalPrice,
                'status' => "new",
                'payment_status' => $request->payment_status,
                'payment_method' => $request->payment_method,
                'shipping_method' => $request->shipping_method,
                // 'paid_at',
                // 'amount_paid',
                'shipping_fees' => $request->shipping_fees,
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
                'ref_id' => $request->ref_id,
                'order_items' => $cart_items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product->name, // Assuming product relationship
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'discount' => 0,
                        'description' => "No description",
                    ];
                })->toArray(),
            ]);
    
            // Clear the cart
            if ($order) {
                $cart->cartItems()->delete();
                session()->forget('cart');
                return redirect()->route('shop')->with('success', 'Order has been placed successfully');
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
        // dd($order->products->first()->name);
        
        return view('orders.show', [
            'selected_order' => $order,
            'countries' => Country::all(),
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
        

    }

    public function track(Order $order)
    {
        dd('Not available at the moment');

    }
    public function dispatch(Request $request, Order $order = new Order())
    {   
        if ($request->isMethod('get')) {
            return view('orders.dispatch', ['selected_order'=>$order]);
        }elseif ($request->isMethod('post')) {
            $order->update([
                'status' => 'dispatched',
                'courier_id' => $request->courier_id,
                'store_id' => $request->store_id,
                // 'dispatched_at' => now(),
            ]);
            return redirect()->route('orders.show', $order)->with('success', 'Order has been dispatched successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order has been deleted successfully');
    }
}
