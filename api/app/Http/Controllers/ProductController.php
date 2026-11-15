<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Flasher\Laravel\Facade as Flasher;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // 'admin',
            new Middleware('admin', except: ['show', 'unfavourite', 'search']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index', [
            'products' => Product::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create',['categories' => Category::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // dd($request->all());
        try {
            $filename = null;
            $file_path = null;

            if ($request->hasFile('image_url')) {
                $filename = time(). '-' . $request->slug. '.' . $request->image_url->extension();
                $file_path = $request->image_url->storeAs('images/product_images',  $filename, 'public');
            }
           $product = Product::create([
               'name' => $request->name,
               'description' => $request->description,
               'slug' => $request->slug,
               'price' => $request->price,
               'stock' => $request->stock,
               'image_url' => $file_path,
               'is_active' => $request->is_active ? 1 : 0,
               'category_id' => $request->category_id,
               'user_id' => Auth::user()->id,
           ]);
            // handle multiple images upload
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $filename = time() . "-{$index}-" . $request->slug . '.' . $image->extension();
                    $path = $image->storeAs('images/product_images', $filename, 'public');
                    $img = ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'sort_order' => $index,
                    ]);
                    if (!$product->image_url) {
                        $product->image_url = $path;
                        $product->save();
                    }
                }
            }

            // set explicit primary image if provided
            if ($request->filled('primary_image')) {
                $primary = ProductImage::where('id', $request->primary_image)->where('product_id', $product->id)->first();
                if ($primary) {
                    $product->image_url = $primary->image_url;
                    $product->save();
                }
            }
       } catch (\Throwable $th) {
        throw $th;
       }

        flash()->success('Product created successfully');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product
        ]);
    }
    public function adminShow(Product $product)
    {
        return view('products.admin_show', [
            'selected_product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'selected_product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        // dd($request->all());
        try {
            $file_path = $product->image_url;

            // Single primary upload (optional)
            // if ($request->hasFile('image_url')) {
            //     if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            //         Storage::disk('public')->delete($product->image_url);
            //     }

            //     $filename = time() . '-' . $request->slug . '.' . $request->image_url->extension();
            //     $file_path = $request->image_url->storeAs('images/product_images', $filename, 'public');
            // }

            // Remove selected images
            if ($request->filled('remove_images')) {
                foreach ($request->remove_images as $removeId) {
                    $img = ProductImage::where('id', $removeId)->where('product_id', $product->id)->first();
                    if ($img) {
                        if ($img->image_url && Storage::disk('public')->exists($img->image_url)) {
                            Storage::disk('public')->delete($img->image_url);
                        }
                        // If the removed image was the product primary, clear it so we can set another
                        if ($product->image_url === $img->image_url) {
                            $file_path = null;
                        }
                        $img->delete();
                    }
                }
            }

            // Add newly uploaded gallery images
            if ($request->hasFile('images')) {
                $maxSort = $product->images()->max('sort_order') ?? 0;
                foreach ($request->file('images') as $index => $image) {
                    $filename = time() . "-{$index}-" . $request->slug . '.' . $image->extension();
                    $path = $image->storeAs('images/product_images', $filename, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $path,
                        'sort_order' => ++$maxSort,
                    ]);
                    if (!$file_path) {
                        $file_path = $path;
                    }
                }
            }

            // Set primary image from selection
            if ($request->filled('primary_image')) {
                $primary = ProductImage::where('id', $request->primary_image)->where('product_id', $product->id)->first();
                if ($primary) {
                    $file_path = $primary->image_url;
                }
            }

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $request->slug,
                'price' => $request->price,
                'stock' => $request->stock,
                'image_url' => $file_path,
                'is_active' => $request->is_active ? 1 : 0,
                'category_id' => $request->category_id,
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }

        flash()->success('Product updated successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image_url && Storage::disk('public')->exists($product->image_url)) {
            Storage::disk('public')->delete($product->image_url);
        }
        $product_images = $product->images;
        if ($product_images) {
            foreach ($product_images as $image) {
                if ($image->image_url && Storage::disk('public')->exists($image->image_url)) {
                    Storage::disk('public')->delete($image->image_url);
                }
            }
        }
        foreach ($product->favourites as $favourite) {
            $favourite->delete();
        }

        $product->delete();

        flash()->success('Product deleted successfully');
        return redirect()->back();
    }

    public function unfavourite(Product $product)
    {
        $favourite = Favourite::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if (!$favourite) {
            flash()->error('This product is not in your favourites.');
            return redirect()->back();
        }

        $favourite->delete();

        flash()->success('Product removed from your favourites');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $search = $request->query('search', $request->input('search'));
        $categoryId = $request->query('category_id', $request->input('category_id'));

        $products = Product::where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                }
            })->when(!empty($categoryId), function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            }, function ($query) {
                // If no category_id is provided, allow products with or without a category
                $query->orWhereNull('category_id');
            })->get();

        return view('shop', [
            'products' => $products,
            "search_term" => $search,
            "serch_category" => $categoryId,
        ]);
    }
}
