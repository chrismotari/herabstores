<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Actions\Orders\CancelOrder;
use App\Actions\Orders\DispatchOrder;
use App\Actions\Orders\MarkOrderDelivered;
use App\Enums\OrderStatus;
use App\Filament\Resources\Orders\OrderResource;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Store;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property Order $record
 */
class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dispatch')
                ->icon('heroicon-o-truck')->color('warning')
                ->visible(fn () => $this->record->status->canTransitionTo(OrderStatus::Dispatched))
                ->schema([
                    Select::make('courier_id')->label('Courier')->required()
                        ->options(fn () => Courier::with('user')->where('is_active', true)->get()->pluck('name', 'id')),
                    Select::make('store_id')->label('From store')
                        ->options(fn () => Store::pluck('name', 'id')),
                ])
                ->action(function (array $data, DispatchOrder $dispatch) {
                    $dispatch($this->record, Courier::findOrFail($data['courier_id']), isset($data['store_id']) ? Store::find($data['store_id']) : null);
                    Notification::make()->success()->title('Order dispatched')->send();
                }),

            Action::make('delivered')
                ->label('Mark delivered')
                ->icon('heroicon-o-check-circle')->color('success')
                ->visible(fn () => $this->record->status->canTransitionTo(OrderStatus::Delivered))
                ->requiresConfirmation()
                ->modalDescription('Confirms delivery and that payment was collected.')
                ->action(function (MarkOrderDelivered $deliver) {
                    $deliver($this->record);
                    Notification::make()->success()->title('Order delivered')->send();
                }),

            Action::make('cancel')
                ->icon('heroicon-o-x-circle')->color('danger')
                ->visible(fn () => $this->record->status->canTransitionTo(OrderStatus::Canceled))
                ->requiresConfirmation()
                ->modalDescription('Stock for every item goes back on the shelf.')
                ->schema([Textarea::make('reason')->rows(2)])
                ->action(function (array $data, CancelOrder $cancel) {
                    $cancel($this->record, $data['reason'] ?? null);
                    $this->record->refresh();
                    Notification::make()->success()->title('Order canceled')->send();
                }),
        ];
    }
}
