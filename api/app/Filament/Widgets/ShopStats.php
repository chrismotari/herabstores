<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ShopStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $notCanceled = fn () => Order::where('status', '!=', OrderStatus::Canceled);
        $sales = fn ($query) => (float) $query->sum(DB::raw('total + shipping_fee'));

        $todayOrders = $notCanceled()->whereDate('created_at', today())->count();
        $todaySales = $sales($notCanceled()->whereDate('created_at', today()));
        $weekSales = $sales($notCanceled()->where('created_at', '>=', today()->subDays(6)));
        $awaiting = Order::where('status', OrderStatus::New)->count();
        $lowStock = Product::where('is_active', true)->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)->count();

        return [
            Stat::make('Orders today', $todayOrders),
            Stat::make('Sales today', 'KES '.number_format($todaySales))
                ->description('KES '.number_format($weekSales).' in the last 7 days'),
            Stat::make('Waiting to dispatch', $awaiting)
                ->color($awaiting ? 'warning' : 'success')
                ->url(OrderResource::getUrl('index', ['filters' => ['status' => ['values' => [OrderStatus::New->value]]]])),
            Stat::make('Low or out of stock', $lowStock)
                ->description('Visible products with ≤ '.Product::LOW_STOCK_THRESHOLD.' left')
                ->color($lowStock ? 'danger' : 'success')
                ->url(ProductResource::getUrl('index', ['filters' => ['low_stock' => ['isActive' => true]]])),
        ];
    }
}
