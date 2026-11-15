<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class SeoController extends Controller
{
    /**
     * Every public catalog URL with its last change, for the storefront sitemap.
     *
     * @unauthenticated
     *
     * @response array{data: array{products: list<array{slug: string, updated_at: string}>, categories: list<array{slug: string, updated_at: string}>, baskets: list<array{slug: string, updated_at: string}>}}
     */
    public function sitemap(): JsonResponse
    {
        $entries = fn ($query) => $query->get(['slug', 'updated_at'])
            ->map(fn ($row) => ['slug' => $row->slug, 'updated_at' => $row->updated_at?->toIso8601String()])
            ->values();

        return response()->json(['data' => [
            'products' => $entries(Product::available()->orderBy('slug')),
            'categories' => $entries(Category::active()->orderBy('slug')),
            'baskets' => $entries(Basket::available()->whereNotNull('slug')->orderBy('slug')),
        ]]);
    }
}
