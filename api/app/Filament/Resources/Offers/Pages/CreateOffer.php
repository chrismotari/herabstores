<?php

namespace App\Filament\Resources\Offers\Pages;

use App\Filament\Resources\Offers\OfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOffer extends CreateRecord
{
    protected static string $resource = OfferResource::class;

    /** Hidden scope fields aren't submitted: clear them so "whole cart" really means no category/product. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category_id'] ??= null;
        $data['product_id'] ??= null;

        return $data;
    }
}
