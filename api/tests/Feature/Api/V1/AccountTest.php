<?php

use App\Models\Cart;
use App\Models\DeliveryZone;
use App\Models\Favourite;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'sanctum');
});

// Profile

test('updates profile and default delivery address', function () {
    $zone = DeliveryZone::factory()->create();

    $this->patchJson('/api/v1/me', ['fname' => 'Amina', 'address' => '5 Moi Ave', 'city' => 'Mombasa', 'delivery_zone_id' => $zone->id])
        ->assertOk()
        ->assertJsonPath('data.fname', 'Amina')
        ->assertJsonPath('data.address', '5 Moi Ave')
        ->assertJsonPath('data.delivery_zone_id', $zone->id);
});

test('changing email marks it unverified; duplicates are rejected', function () {
    $other = User::factory()->create();

    $this->patchJson('/api/v1/me', ['email' => $other->email])->assertJsonValidationErrors('email');
    $this->patchJson('/api/v1/me', ['email' => 'New@Example.com'])->assertJsonPath('data.email_verified', false);

    expect($this->user->fresh()->email)->toBe('new@example.com');
});

test('profile updates cannot change protected fields', function () {
    $this->patchJson('/api/v1/me', ['is_admin' => true, 'is_active' => false, 'password' => 'x'])->assertOk();

    $fresh = $this->user->fresh();
    expect($fresh->is_admin)->toBeFalsy()
        ->and($fresh->is_active)->toBeTruthy()
        ->and(Hash::check('password', $fresh->password))->toBeTrue();
});

test('changing password needs the current one and revokes other tokens', function () {
    $user = User::factory()->create();
    $current = $user->createToken('this-phone');
    $user->createToken('old-tablet');
    app('auth')->forgetGuards();

    $this->withToken($current->plainTextToken)
        ->putJson('/api/v1/me/password', ['current_password' => 'wrong', 'password' => 'new-password', 'password_confirmation' => 'new-password'])
        ->assertJsonValidationErrors('current_password');

    $this->withToken($current->plainTextToken)
        ->putJson('/api/v1/me/password', ['current_password' => 'password', 'password' => 'new-password', 'password_confirmation' => 'new-password'])
        ->assertOk();

    expect($user->tokens()->pluck('name')->all())->toBe(['this-phone']);
});

// Orders

test('lists only my orders, newest first', function () {
    $old = Order::factory()->for($this->user)->create(['created_at' => now()->subDay()]);
    $new = Order::factory()->for($this->user)->create();
    Order::factory()->create();

    $this->getJson('/api/v1/me/orders')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.id', $new->id)
        ->assertJsonPath('data.1.id', $old->id);

    $this->getJson('/api/v1/me/orders?page=2')->assertOk()->assertJsonCount(0, 'data');
    $this->getJson('/api/v1/me/orders?page=x')->assertJsonValidationErrorFor('page');
});

test('shows my order; someone else\'s is a 404', function () {
    $mine = Order::factory()->for($this->user)->create();
    $theirs = Order::factory()->create();

    $this->getJson("/api/v1/me/orders/{$mine->id}")->assertOk()->assertJsonPath('data.order_number', $mine->order_number);
    $this->getJson("/api/v1/me/orders/{$theirs->id}")->assertNotFound();
});

// Favourites

test('adds, lists and removes favourites idempotently', function () {
    $product = Product::factory()->create();

    $this->postJson('/api/v1/me/favourites', ['product_id' => $product->id])->assertCreated();
    $this->postJson('/api/v1/me/favourites', ['product_id' => $product->id])->assertCreated();
    $this->getJson('/api/v1/me/favourites')->assertJsonCount(1, 'data')->assertJsonPath('data.0.id', $product->id);

    $this->deleteJson("/api/v1/me/favourites/{$product->id}")->assertOk();
    $this->deleteJson("/api/v1/me/favourites/{$product->id}")->assertOk();
    $this->getJson('/api/v1/me/favourites')->assertJsonCount(0, 'data');
});

test('favourites hide products that are no longer available', function () {
    $product = Product::factory()->create();
    Favourite::create(['user_id' => $this->user->id, 'product_id' => $product->id]);
    $product->update(['is_active' => false]);

    $this->getJson('/api/v1/me/favourites')->assertJsonCount(0, 'data');
});

// Deletion

test('deleting the account keeps orders but unlinks them', function () {
    $order = Order::factory()->for($this->user)->create();
    Favourite::create(['user_id' => $this->user->id, 'product_id' => Product::factory()->create()->id]);
    $this->user->carts()->create()->cartItems()->create(['product_id' => Product::factory()->create()->id, 'quantity' => 1]);
    $this->user->createToken('phone');

    $this->deleteJson('/api/v1/me', ['password' => 'wrong'])->assertJsonValidationErrors('password');
    $this->deleteJson('/api/v1/me', ['password' => 'password'])->assertOk();

    expect(User::find($this->user->id))->toBeNull()
        ->and($order->fresh())->not->toBeNull()
        ->and($order->fresh()->user_id)->toBeNull()
        ->and(Favourite::count())->toBe(0)
        ->and(Cart::count())->toBe(0)
        ->and(PersonalAccessToken::count())->toBe(0);
});
