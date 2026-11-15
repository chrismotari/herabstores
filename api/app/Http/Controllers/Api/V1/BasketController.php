<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BasketResource;
use App\Models\Basket;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BasketController extends Controller
{
    /**
     * List baskets available now.
     *
     * @unauthenticated
     */
    public function index(): AnonymousResourceCollection
    {
        return BasketResource::collection(Basket::available()->orderBy('name')->get());
    }

    /**
     * Show an available basket by slug, with its products.
     *
     * @unauthenticated
     */
    public function show(string $slug): BasketResource
    {
        return new BasketResource(
            Basket::available()->with('basket_products.product.category')->where('slug', $slug)->firstOrFail()
        );
    }
}
