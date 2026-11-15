<?php

namespace App\Http\Resources\V1;

use App\Models\Category;
use App\Support\MediaUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Category */
class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => MediaUrl::for($this->image_url),
            'parent_id' => $this->parent_id,
            'products_count' => $this->whenCounted('products'),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
