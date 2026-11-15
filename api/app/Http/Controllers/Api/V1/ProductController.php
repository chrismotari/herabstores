<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public const SORTS = ['newest', 'price_asc', 'price_desc', 'name'];

    /**
     * List available products (paginated).
     *
     * @unauthenticated
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->validate([
            /** Search in name and description. */
            'q' => ['nullable', 'string', 'max:100'],
            /** Category slug. */
            'category' => ['nullable', 'string', 'max:255'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            /** Only products with stock. */
            'in_stock' => ['nullable', 'boolean'],
            'sort' => ['nullable', Rule::in(self::SORTS)],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:60'],
        ]);

        $products = Product::available()
            ->with('category')
            ->when($filters['q'] ?? null, function ($query, string $q) {
                $like = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $q).'%';
                $query->where(fn ($w) => $w->where('name', 'ilike', $like)->orWhere('description', 'ilike', $like));
            })
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('category', fn ($c) => $c->where('slug', $slug)))
            ->when(isset($filters['min_price']), fn ($query) => $query->where('price', '>=', $filters['min_price']))
            ->when(isset($filters['max_price']), fn ($query) => $query->where('price', '<=', $filters['max_price']))
            ->when($request->boolean('in_stock'), fn ($query) => $query->where('stock', '>', 0))
            ->tap(fn ($query) => match ($filters['sort'] ?? 'newest') {
                'price_asc' => $query->orderBy('price')->orderBy('name'),
                'price_desc' => $query->orderByDesc('price')->orderBy('name'),
                'name' => $query->orderBy('name'),
                default => $query->latest()->orderBy('name'),
            })
            ->paginate($filters['per_page'] ?? 24)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Show an available product by slug (an id is also accepted, for old links).
     *
     * @unauthenticated
     */
    public function show(string $slug): ProductResource
    {
        return new ProductResource(
            Product::available()->with(['category', 'images'])
                // Old storefront URLs used the id: /products/{uuid}. Clients redirect to the slug.
                ->where(fn ($q) => Str::isUuid($slug) ? $q->whereKey($slug) : $q->where('slug', $slug))
                ->firstOrFail()
        );
    }
}
