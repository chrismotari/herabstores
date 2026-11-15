<?php

namespace App\Http\Controllers;

use App\Actions\Orders\PlaceOrder;
use App\Http\Requests\PlaceOrderRequest;
use App\Models\Country;
use App\Models\Order;
use App\Models\Ref;
use App\Services\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Storefront checkout and order pages. Order management is in Filament (/admin).
 */
class OrderController extends Controller
{
    public function create()
    {
        return view('orders.create', [
            'selected_user' => Auth::user(),
            'cart' => app(CartService::class)->current(request()),
            'refs' => Ref::where('is_active', true)->get(),
        ]);
    }

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

    public function show(Order $order)
    {
        return view('orders.show', ['selected_order' => $order, 'countries' => Country::all()]);
    }

    /**
     * Tracking = the order page (status and timestamps).
     */
    public function track(Order $order)
    {
        return $this->show($order);
    }
}
