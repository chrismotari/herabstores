<?php

use App\Enums\OrderStatus;
use App\Filament\Widgets\LowStockProducts;
use App\Filament\Widgets\ShopStats;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
    Filament::setCurrentPanel('admin');
});

test('the dashboard renders with widgets', function () {
    Order::factory()->create(['total' => 1000, 'shipping_fee' => 200]);
    Product::factory()->create(['stock' => 1]);

    $this->get('/admin')->assertOk();
});

test('stats count today\'s non-canceled orders and sales', function () {
    Order::factory()->create(['total' => 1000, 'shipping_fee' => 200, 'status' => OrderStatus::New]);
    Order::factory()->create(['total' => 500, 'shipping_fee' => 100, 'status' => OrderStatus::Delivered]);
    Order::factory()->create(['total' => 9999, 'shipping_fee' => 0, 'status' => OrderStatus::Canceled]);
    Order::factory()->create(['total' => 300, 'shipping_fee' => 0, 'created_at' => now()->subDays(3)]);

    Livewire::test(ShopStats::class)
        ->assertSee('Orders today')
        ->assertSeeInOrder(['Orders today', '2'])
        ->assertSee('KES 1,800')
        ->assertSee('KES 2,100 in the last 7 days')
        ->assertSeeInOrder(['Waiting to dispatch', '2']);
});

test('low stock widget lists visible low-stock products and allows editing stock', function () {
    $low = Product::factory()->create(['name' => 'Almost Gone', 'stock' => 2]);
    Product::factory()->create(['name' => 'Plenty', 'stock' => 50]);
    Product::factory()->create(['name' => 'Hidden Low', 'stock' => 0, 'is_active' => false]);

    Livewire::test(LowStockProducts::class)
        ->assertCanSeeTableRecords([$low])
        ->assertSee('Almost Gone')
        ->assertDontSee('Plenty')
        ->assertDontSee('Hidden Low')
        ->call('updateTableColumnState', 'stock', $low->getKey(), 40);

    expect($low->fresh()->stock)->toBe(40);
});
