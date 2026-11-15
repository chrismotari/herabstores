<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * List active categories with their number of available products.
     *
     * @unauthenticated
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::active()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Show an active category by slug.
     *
     * @unauthenticated
     */
    public function show(string $slug): CategoryResource
    {
        return new CategoryResource(
            Category::active()
                ->withCount(['products' => fn ($q) => $q->where('is_active', true)])
                ->where('slug', $slug)
                ->firstOrFail()
        );
    }
}
