<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Orders\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\V1\DeliveryZoneResource;
use App\Http\Resources\V1\OrderResource;
use App\Models\DeliveryZone;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CheckoutController extends Controller
{
    /**
     * Delivery zones customers can choose at checkout, with their fee.
     *
     * @unauthenticated
     */
    public function deliveryZones(): AnonymousResourceCollection
    {
        return DeliveryZoneResource::collection(
            DeliveryZone::where('is_active', true)->orderBy('name')->get()
        );
    }

    /**
     * Place a pay-on-delivery order from the current cart.
     *
     * Guests send their X-Cart-Token. Stock is checked and reserved; problems
     * come back as a 422 with errors under `cart`.
     *
     * @unauthenticated
     */
    public function placeOrder(PlaceOrderRequest $request, CartService $carts, PlaceOrder $placeOrder): JsonResponse
    {
        $user = $request->user('sanctum');
        $order = $placeOrder($carts->current($request), $request->validated(), $user);

        return (new OrderResource($order->load('deliveryZone')))->response()->setStatusCode(201);
    }
}
