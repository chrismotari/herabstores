<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;

test('abandoned guest carts are pruned with their items; recent and customer carts are kept', function () {
    $product = Product::factory()->create();
    $old = Cart::create(['token' => (string) Str::uuid()]);
    $old->cartItems()->create(['product_id' => $product->id, 'quantity' => 1]);
    $recent = Cart::create(['token' => (string) Str::uuid()]);
    $customer = User::factory()->create()->carts()->create(['status' => 'open']);
    Cart::whereKey([$old->id, $customer->id])->update(['updated_at' => now()->subDays(Cart::GUEST_CART_DAYS + 1)]);

    $this->artisan('model:prune', ['--model' => [Cart::class]])->assertSuccessful();

    expect(Cart::find($old->id))->toBeNull()
        ->and(Cart::find($recent->id))->not->toBeNull()
        ->and(Cart::find($customer->id))->not->toBeNull()
        ->and(CartItem::count())->toBe(0);
});

test('changing an item keeps a guest cart alive', function () {
    $cart = Cart::create(['token' => (string) Str::uuid()]);
    Cart::whereKey($cart->id)->update(['updated_at' => now()->subDays(Cart::GUEST_CART_DAYS + 1)]);

    $cart->cartItems()->create(['product_id' => Product::factory()->create()->id, 'quantity' => 1]);

    expect($cart->fresh()->updated_at->isToday())->toBeTrue();
});

test('housekeeping commands are scheduled', function () {
    $commands = collect(app(Schedule::class)->events())->map->command->implode("\n");

    expect($commands)->toContain('model:prune')
        ->toContain('sanctum:prune-expired')
        ->toContain('auth:clear-resets')
        ->toContain('queue:prune-failed');
});
