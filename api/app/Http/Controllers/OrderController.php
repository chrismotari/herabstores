<?php

namespace App\Http\Controllers;

use App\Actions\Orders\PlaceOrder;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\Ref;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        return view('orders.create', [
            'selected_user' => Auth::user(),
            'cart' => app(CartService::class)->current(request()),
            'refs' => Ref::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlaceOrderRequest $request, CartService $carts, PlaceOrder $placeOrder)
    {
        try {
            $placeOrder($carts->current($request), $request->validated(), $request->user());
        } catch (ValidationException $e) {
            flash()->error(collect($e->errors())->flatten()->first());

            return back()->withErrors($e->errors())->withInput();
        }

        $request->session()->forget(CartService::SESSION_KEY);
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
