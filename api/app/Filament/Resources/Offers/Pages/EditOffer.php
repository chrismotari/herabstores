<?php

namespace App\Filament\Resources\Offers\Pages;

use App\Filament\Resources\Offers\OfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /** Hidden scope fields aren't submitted: clear them so "whole cart" really means no category/product. */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['category_id'] ??= null;
        $data['product_id'] ??= null;

        return $data;
    }
}
