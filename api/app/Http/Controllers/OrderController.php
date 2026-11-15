<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Models\Contact;
use App\Models\Country;
use App\Models\DeliveryZone;
use App\Models\Estate;
use App\Models\Order;
use App\Models\Ref;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $cartService = new CartService;

        return view('orders.create', [
            'selected_user' => Auth::user(),
            'cart' => auth()->check() ? auth()->user()->cart : $cartService->getGuestCart(),
            'estates' => Estate::all(),
            'refs' => Ref::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlaceOrderRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $cart = $user ? $user->cart : (new CartService)->getGuestCart();
        $cartItems = $cart?->cartItems ?? collect();

        if ($cartItems->isEmpty()) {
            flash()->error('Cart is empty');

            return redirect()->back();
        }

        $zone = DeliveryZone::findOrFail($data['delivery_zone_id']);
        $estate = isset($data['estate_id']) ? Estate::find($data['estate_id']) : null;
        // The checkout page shows the estate's fee; fall back to the zone's fee.
        $shippingFee = $estate?->delivery_fees ?? $zone->delivery_fees;

        $order = DB::transaction(function () use ($data, $user, $cart, $cartItems, $zone, $shippingFee) {
            $order = Order::create([
                'user_id' => $user?->id,
                'total' => $cartItems->sum(fn ($item) => $item->product->price * $item->quantity),
                'shipping_fee' => $shippingFee,
                'status' => 'new',
                'payment_method' => 'cash_on_delivery',
                'payment_status' => 'pending',
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'phone' => $data['phone'],
                'alt_phone' => $data['alt_phone'] ?? null,
                'email' => $data['email'] ?? null,
                'address' => $data['address'],
                'city' => $data['city'],
                'delivery_zone' => $zone->id,
                'message' => $data['message'] ?? null,
                'ref_id' => $data['ref_id'] ?? null,
                'order_items' => $cartItems->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'discount' => 0,
                    'description' => 'No description',
                ])->values()->all(),
            ]);

            // Sign-up doesn't ask for an address, so remember the first one used at checkout.
            if ($user && blank($user->address)) {
                $user->update([
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'delivery_zone_id' => $zone->id,
                ]);
            }

            if ($user) {
                $cart->cartItems()->delete();
            }

            return $order;
        });

        session()->forget('cart');

        if ($request->boolean('consented')) {
            Contact::create([
                'email' => $data['email'] ?? null,
                'name' => "{$data['fname']} {$data['lname']}",
                'phone' => $data['phone'],
                'consented' => true,
                'source' => 'Purchase',
            ]);
        }

        flash()->success('Order has been placed successfully');

        return redirect()->route('shop')->with('success', 'Order has been placed successfully');
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
    public function update(Request $request, Order $order) {}

    public function track(Order $order)
    {
        dd('Not available at the moment');

    }

    public function dispatch(Request $request, Order $order = new Order)
    {
        if ($request->isMethod('get')) {
            return view('orders.dispatch', ['selected_order' => $order]);
        } elseif ($request->isMethod('post')) {
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
