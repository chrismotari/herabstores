<?php

namespace App\Actions\Orders;

use App\Models\Cart;
use App\Models\Contact;
use App\Models\DeliveryZone;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Places a pay-on-delivery order from a cart. Used by web and API checkout.
 *
 * In one transaction: locks the products, checks stock and active status,
 * decrements stock, records the order with price snapshots, saves the
 * customer's first address, and empties the cart (guest carts are deleted).
 *
 * @param  array{fname: string, lname: string, phone: string, alt_phone?: ?string, email?: ?string, address: string, city: string, delivery_zone_id: string, ref_id?: ?string, message?: ?string, consented?: mixed}  $data
 */
class PlaceOrder
{
    public function __invoke(Cart $cart, array $data, ?User $user = null): Order
    {
        if (! $cart->exists || $cart->cartItems()->doesntExist()) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        $order = DB::transaction(function () use ($cart, $data, $user) {
            $items = $cart->cartItems()->get();
            // Lock in a stable order so concurrent checkouts can't deadlock or oversell.
            $products = Product::whereKey($items->pluck('product_id'))
                ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

            $problems = [];
            foreach ($items as $item) {
                $product = $products[$item->product_id];
                if (! $product->is_active) {
                    $problems[] = "{$product->name} is no longer available.";
                } elseif ($item->quantity > $product->stock) {
                    $problems[] = $product->stock < 1
                        ? "{$product->name} is out of stock."
                        : "Only {$product->stock} of {$product->name} left.";
                }
            }
            if ($problems) {
                throw ValidationException::withMessages(['cart' => $problems]);
            }

            // Delivery is priced per zone (see spec decision log).
            $zone = DeliveryZone::findOrFail($data['delivery_zone_id']);

            $order = Order::create([
                'user_id' => $user?->id,
                'total' => $items->sum(fn ($item) => $products[$item->product_id]->price * $item->quantity),
                'shipping_fee' => $zone->delivery_fees,
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
                'order_items' => $items->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'name' => $products[$item->product_id]->name,
                    'price' => $products[$item->product_id]->price,
                    'quantity' => $item->quantity,
                    'discount' => 0,
                    'description' => 'No description',
                ])->values()->all(),
            ]);

            foreach ($items as $item) {
                $products[$item->product_id]->decrement('stock', $item->quantity);
            }

            // Sign-up doesn't ask for an address, so remember the first one used at checkout.
            if ($user && blank($user->address)) {
                $user->update([
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'delivery_zone_id' => $zone->id,
                ]);
            }

            $cart->cartItems()->delete();
            if (! $user) {
                $cart->delete();
            }

            return $order;
        });

        if (filter_var($data['consented'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            Contact::create([
                'email' => $data['email'] ?? null,
                'name' => "{$data['fname']} {$data['lname']}",
                'phone' => $data['phone'],
                'consented' => true,
                'source' => 'Purchase',
            ]);
        }

        return $order;
    }
}
