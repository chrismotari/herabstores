<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Storefront product pages. Product management is in Filament (/admin).
 */
class ProductController extends Controller
{
    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product,
        ]);
    }

    public function unfavourite(Product $product)
    {
        $deleted = Favourite::where('user_id', auth()->id())->where('product_id', $product->id)->delete();

        $deleted
            ? flash()->success('Product removed from your favourites')
            : flash()->error('This product is not in your favourites.');

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $search = $request->query('search', $request->input('search'));
        $categoryId = $request->query('category_id', $request->input('category_id'));

        $products = Product::where(function ($query) use ($search) {
            if (! empty($search)) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            }
        })->when(! empty($categoryId), function ($query) use ($categoryId) {
            $query->where('category_id', $categoryId);
        }, function ($query) {
            // If no category_id is provided, allow products with or without a category
            $query->orWhereNull('category_id');
        })->get();

        return view('shop', [
            'products' => $products,
            'search_term' => $search,
            'serch_category' => $categoryId,
        ]);
    }
}
