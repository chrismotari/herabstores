<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Account\DeleteAccount;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Favourite;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * The signed-in customer's own data: profile, orders, favourites.
 */
class AccountController extends Controller
{
    /**
     * Update the profile, including the default delivery address.
     *
     * Changing the email marks it unverified.
     */
    public function update(Request $request): UserResource
    {
        $user = $request->user();
        $data = $request->validate([
            'fname' => ['sometimes', 'required', 'string', 'max:255'],
            'mname' => ['sometimes', 'nullable', 'string', 'max:255'],
            'lname' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'delivery_zone_id' => ['sometimes', 'nullable', 'uuid', Rule::exists('delivery_zones', 'id')->where('is_active', true)],
        ]);

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email = strtolower($user->email);
            $user->email_verified_at = null;
        }
        $user->save();

        return new UserResource($user);
    }

    /**
     * Change password. Other devices' tokens are revoked.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password:sanctum'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->update(['password' => Hash::make($request->input('password'))]);

        $current = $user->currentAccessToken();
        $user->tokens()->when($current?->id, fn ($q, $id) => $q->whereKeyNot($id))->delete();

        return response()->json(['message' => 'Password updated.']);
    }

    /**
     * Delete the account. Requires the password.
     *
     * Orders are kept for records but unlinked from the account.
     */
    public function destroy(Request $request, DeleteAccount $delete): JsonResponse
    {
        $request->validate(['password' => ['required', 'current_password:sanctum']]);

        $delete($request->user());

        if ($request->hasSession()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
        }

        return response()->json(['message' => 'Account deleted.']);
    }

    /**
     * My orders, newest first.
     */
    public function orders(Request $request): AnonymousResourceCollection
    {
        return OrderResource::collection(
            $request->user()->orders()->with('deliveryZone')->latest()->paginate(20)
        );
    }

    /**
     * One of my orders (includes its status, for tracking).
     */
    public function order(Request $request, Order $order): OrderResource
    {
        abort_unless($order->user_id === $request->user()->id, 404);

        return new OrderResource($order->load('deliveryZone'));
    }

    /**
     * My favourite products.
     */
    public function favourites(Request $request): AnonymousResourceCollection
    {
        return ProductResource::collection(
            Product::available()->with('category')
                ->whereIn('id', $request->user()->favourites()->select('product_id'))
                ->orderBy('name')->get()
        );
    }

    /**
     * Add a favourite (idempotent).
     */
    public function addFavourite(Request $request): JsonResponse
    {
        $data = $request->validate(['product_id' => ['required', 'uuid', 'exists:products,id']]);

        Favourite::firstOrCreate(['user_id' => $request->user()->id, 'product_id' => $data['product_id']]);

        return response()->json(['message' => 'Added to favourites.'], 201);
    }

    /**
     * Remove a favourite (idempotent).
     */
    public function removeFavourite(Request $request, string $product): JsonResponse
    {
        $request->user()->favourites()->where('product_id', $product)->delete();

        return response()->json(['message' => 'Removed from favourites.']);
    }
}
