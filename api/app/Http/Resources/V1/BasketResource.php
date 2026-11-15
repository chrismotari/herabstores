<?php

namespace App\Http\Resources\V1;

use App\Models\Basket;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Basket */
class BasketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => (float) $this->price,
            'in_stock' => $this->stock > 0,
            /** Maximum quantity that can be ordered right now. */
            'available_quantity' => (int) max(0, (int) $this->stock),
            'image_url' => MediaUrl::for($this->image),
            'available_from' => $this->effective_from?->toIso8601String(),
            'available_until' => $this->effective_to?->toIso8601String(),
            'items' => $this->whenLoaded('basket_products', fn () => $this->basket_products
                ->filter(fn ($item) => $item->is_active && $item->product)
                ->map(fn ($item) => [
                    'quantity' => $item->quantity,
                    'product' => new ProductResource($item->product),
                ])->values()),
        ];
    }
}
