<?php

namespace App\Http\Resources\V1;

use App\Models\Product;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
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
            /** "In Stock", "Few Items Left" or "Out of Stock". */
            'stock_status' => $this->is_in_stock,
            /** Maximum quantity that can be ordered right now. */
            'available_quantity' => (int) max(0, (int) $this->stock),
            'image_url' => MediaUrl::for($this->image_url),
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => MediaUrl::for($image->image_url))->values()),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
