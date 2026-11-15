<?php

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\DispatchOrder;
use App\Actions\Orders\MarkOrderDelivered;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Models\Basket;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

function orderFor(Product $product, int $qty = 2, ?Basket $basket = null): Order
{
    $items = [['type' => 'product', 'product_id' => $product->id, 'basket_id' => null, 'name' => $product->name, 'price' => $product->price, 'quantity' => $qty]];
    if ($basket) {
        $items[] = ['type' => 'basket', 'product_id' => null, 'basket_id' => $basket->id, 'name' => $basket->name, 'price' => $basket->price, 'quantity' => 1];
    }

    return Order::factory()->create(['order_items' => $items, 'status' => OrderStatus::New]);
}

test('dispatch records courier, store and time', function () {
    $order = orderFor(Product::factory()->create());
    $courier = Courier::factory()->create();
    $store = Store::factory()->create();

    app(DispatchOrder::class)($order, $courier, $store);

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Dispatched)
        ->and($order->courier_id)->toBe($courier->id)
        ->and($order->store_id)->toBe($store->id)
        ->and($order->shipped_at)->not->toBeNull();
});

test('delivery marks the order paid', function () {
    $order = orderFor(Product::factory()->create());
    app(DispatchOrder::class)($order, Courier::factory()->create());

    app(MarkOrderDelivered::class)($order->fresh());

    $order->refresh();
    expect($order->status)->toBe(OrderStatus::Delivered)
        ->and($order->payment_status)->toBe('paid')
        ->and($order->delivered_at)->not->toBeNull();
});

test('canceling restores product and basket stock', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $basket = Basket::factory()->create(['stock' => 2]);
    $order = orderFor($product, 3, $basket);

    app(CancelOrder::class)($order, 'Customer unreachable');

    expect($order->fresh()->status)->toBe(OrderStatus::Canceled)
        ->and($product->fresh()->stock)->toBe(8)
        ->and($basket->fresh()->stock)->toBe(3)
        ->and($order->fresh()->message)->toContain('Customer unreachable');
});

test('invalid transitions are refused', function () {
    $order = orderFor(Product::factory()->create());

    expect(fn () => app(MarkOrderDelivered::class)($order))->toThrow(ValidationException::class);

    app(CancelOrder::class)($order);
    expect(fn () => app(CancelOrder::class)($order->fresh()))->toThrow(ValidationException::class)
        ->and(fn () => app(DispatchOrder::class)($order->fresh(), Courier::factory()->create()))->toThrow(ValidationException::class);
});

test('a canceled order does not restock twice', function () {
    $product = Product::factory()->create(['stock' => 5]);
    $order = orderFor($product, 2);

    app(CancelOrder::class)($order);
    try {
        app(CancelOrder::class)($order);
    } catch (ValidationException) {
    }

    expect($product->fresh()->stock)->toBe(7);
});

// Filament

beforeEach(function () {
    Filament::setCurrentPanel('admin');
});

test('admin order, courier and customer pages render', function (string $url) {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    orderFor(Product::factory()->create());
    Courier::factory()->create();

    $this->get($url)->assertOk();
})->with(['/admin/orders', '/admin/couriers', '/admin/couriers/create', '/admin/customers']);

test('the order page shows details and runs the workflow', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    $order = orderFor(Product::factory()->create(['name' => 'Kericho Gold']));
    $courier = Courier::factory()->create();

    $this->get("/admin/orders/{$order->id}")->assertOk()->assertSee('Kericho Gold')->assertSee($order->order_number);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertActionVisible('dispatch')
        ->assertActionHidden('delivered')
        ->callAction('dispatch', ['courier_id' => $courier->id])
        ->assertHasNoActionErrors();

    expect($order->fresh()->status)->toBe(OrderStatus::Dispatched);

    Livewire::test(ViewOrder::class, ['record' => $order->getRouteKey()])
        ->callAction('delivered');

    expect($order->fresh()->status)->toBe(OrderStatus::Delivered);
});

test('admins cannot be listed or edited as customers', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $this->actingAs($admin);

    $this->get("/admin/customers/{$admin->id}/edit")->assertNotFound();
});
