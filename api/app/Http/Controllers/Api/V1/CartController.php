<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Cart\AddItemToCart;
use App\Actions\Cart\UpdateCartItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResource;
use App\Models\Basket;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The current cart: the logged-in user's, or a guest cart identified by the
 * X-Cart-Token header. Guests get a token from the first add (or POST /cart)
 * and must send it on every cart and checkout request.
 */
class CartController extends Controller
{
    public function __construct(private CartService $carts) {}

    /**
     * Show the current cart.
     *
     * @unauthenticated
     */
    public function show(Request $request): JsonResponse
    {
        return $this->respond($this->carts->current($request));
    }

    /**
     * Create (or return) the current cart. Guests receive a token.
     *
     * @unauthenticated
     */
    public function store(Request $request): JsonResponse
    {
        $cart = $this->carts->current($request, create: true);

        return $this->respond($cart, $cart->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Add a product or basket (adding one already in the cart increases its quantity).
     *
     * @unauthenticated
     */
    public function addItem(Request $request, AddItemToCart $add): JsonResponse
    {
        $data = $request->validate([
            /** Send product_id or basket_id. */
            'product_id' => ['required_without:basket_id', 'prohibits:basket_id', 'uuid', 'exists:products,id'],
            'basket_id' => ['required_without:product_id', 'uuid', 'exists:baskets,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:999'],
        ]);

        $item = isset($data['basket_id']) ? Basket::findOrFail($data['basket_id']) : Product::findOrFail($data['product_id']);
        $cart = $this->carts->current($request, create: true);
        $add($cart, $item, $data['quantity'] ?? 1);

        return $this->respond($cart);
    }

    /**
     * Set an item's quantity (0 removes it).
     *
     * @unauthenticated
     */
    public function updateItem(Request $request, string $item, UpdateCartItem $update): JsonResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:999']]);
        $cart = $this->carts->current($request);

        $update($this->findItem($cart, $item), $data['quantity']);

        return $this->respond($cart);
    }

    /**
     * Remove an item.
     *
     * @unauthenticated
     */
    public function removeItem(Request $request, string $item): JsonResponse
    {
        $cart = $this->carts->current($request);
        $this->findItem($cart, $item)->delete();

        return $this->respond($cart);
    }

    /**
     * Empty the cart.
     *
     * @unauthenticated
     */
    public function clear(Request $request): JsonResponse
    {
        $cart = $this->carts->current($request);
        if ($cart->exists) {
            $cart->cartItems()->delete();
        }

        return $this->respond($cart);
    }

    /** Items can only be changed through the cart that owns them. */
    private function findItem(Cart $cart, string $id): CartItem
    {
        abort_unless($cart->exists, 404);

        return $cart->cartItems()->whereKey($id)->firstOrFail();
    }

    private function respond(Cart $cart, int $status = 200): JsonResponse
    {
        if ($cart->exists) {
            $cart->load(['cartItems.product.category', 'cartItems.basket']);
        }

        return (new CartResource($cart))->response()->setStatusCode($status);
    }
}
