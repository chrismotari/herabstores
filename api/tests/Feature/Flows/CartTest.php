<?php

use App\Models\Basket;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;

test('guest add creates a token cart in the database', function () {
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 2])
        ->assertRedirect();

    $cart = Cart::where('token', session(CartService::SESSION_KEY))->sole();
    expect($cart->user_id)->toBeNull()
        ->and($cart->cartItems->sole()->quantity)->toBe(2);
});

test('adding a product already in the cart increases its quantity', function () {
    $product = Product::factory()->create(['stock' => 10]);

    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1]);
    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 3]);

    expect(Cart::sole()->cartItems->sole()->quantity)->toBe(4);
});

test('cannot add more than is in stock', function () {
    $product = Product::factory()->create(['stock' => 2]);

    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 3])
        ->assertSessionHasErrors('quantity');

    expect(Cart::count())->toBeLessThanOrEqual(1)
        ->and(CartItem::count())->toBe(0);
});

test('cannot add inactive products', function () {
    $product = Product::factory()->create(['is_active' => false]);

    $this->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1])
        ->assertSessionHasErrors('product_id');
});

test('guest can remove a product from the cart', function () {
    $product = Product::factory()->create();

    $session = guestSessionWith($product);
    $item = CartItem::sole();

    $this->withSession($session)
        ->delete(route('cart_items.remove', $item))
        ->assertRedirect();

    expect(CartItem::count())->toBe(0);
});

test('guest can view a non-empty cart', function () {
    $product = Product::factory()->create();

    $this->withSession(guestSessionWith($product))
        ->get(route('carts.show'))
        ->assertOk()
        ->assertSee($product->name);
});

test('adding to cart validates product and quantity', function () {
    $this->post(route('cart_items.store'), ['product_id' => 'nope', 'quantity' => 0])
        ->assertSessionHasErrors(['product_id', 'quantity']);
});

test('logged-in user gets a cart created on first add', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 10]);

    $this->actingAs($user)
        ->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 3])
        ->assertRedirect();

    expect($user->fresh()->cart->cartItems)->toHaveCount(1)
        ->and($user->fresh()->cart->cartItems->first()->quantity)->toBe(3);
});

test('logged-in user can view and remove from their cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    $this->actingAs($user)->post(route('cart_items.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $this->get(route('carts.show'))->assertOk()->assertSee($product->name);

    $this->delete(route('cart_items.remove', $user->fresh()->cart->cartItems->sole()))->assertRedirect();

    expect($user->fresh()->cart->cartItems)->toBeEmpty();
});

test('guest cart merges into the user cart at login', function () {
    $user = User::factory()->create();
    $shared = Product::factory()->create(['stock' => 10]);
    $guestOnly = Product::factory()->create(['stock' => 10]);
    $userCart = $user->carts()->create(['status' => 'open']);
    $userCart->cartItems()->create(['product_id' => $shared->id, 'quantity' => 2]);

    $session = guestSessionWith([$shared, $guestOnly]);

    $this->withSession($session)
        ->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect();

    $items = $userCart->fresh()->cartItems->keyBy('product_id');
    expect($items)->toHaveCount(2)
        ->and($items[$shared->id]->quantity)->toBe(3)
        ->and($items[$guestOnly->id]->quantity)->toBe(1)
        ->and(Cart::whereNull('user_id')->count())->toBe(0);
});

test('merging caps quantities at available stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);
    $user->carts()->create(['status' => 'open'])->cartItems()->create(['product_id' => $product->id, 'quantity' => 3]);

    $this->withSession(guestSessionWith($product))
        ->post('/login', ['email' => $user->email, 'password' => 'password']);

    expect($user->fresh()->cart->cartItems->sole()->quantity)->toBe(3);
});

test('the cart token can come from the X-Cart-Token header', function () {
    $cart = Cart::create(['token' => (string) Str::uuid()]);
    $request = Request::create('/');
    $request->headers->set(CartService::HEADER, $cart->token);

    expect(app(CartService::class)->current($request)->is($cart))->toBeTrue();
});

test('an unknown or malformed token gives an empty, unsaved cart', function (string $token) {
    $request = Request::create('/');
    $request->headers->set(CartService::HEADER, $token);

    $cart = app(CartService::class)->current($request);

    expect($cart->exists)->toBeFalse()
        ->and($cart->cartItems)->toBeEmpty();
})->with(['not-a-uuid', fn () => (string) Str::uuid()]);

test('a guest cannot remove items from another cart', function () {
    $product = Product::factory()->create();
    guestSessionWith($product);
    $item = CartItem::sole();

    $this->withSession(guestSessionWith(Product::factory()->create()))
        ->delete(route('cart_items.remove', $item));

    expect(CartItem::find($item->id))->not->toBeNull();
});

test('the web cart shows basket lines', function () {
    $basket = Basket::factory()->create(['name' => 'Weekly Veg Box', 'price' => 1500]);
    $cart = Cart::create(['token' => (string) Str::uuid()]);
    $cart->cartItems()->create(['basket_id' => $basket->id, 'quantity' => 1]);

    $this->withSession([CartService::SESSION_KEY => $cart->token])
        ->get(route('carts.show'))
        ->assertOk()
        ->assertSee('Weekly Veg Box');
});
