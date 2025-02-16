<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Favourite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Flasher\Laravel\Facade as Flasher;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // 'admin',
            new Middleware('admin', except: ['show', 'unfavourite']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index', [
            'products' => Product::all(),
            'categories' => Category::all()
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
        
       try {
            $request->validated();
            
            $filename = null;
            $file_path = null;

            if ($request->hasFile('image_url')) {
                $filename = time(). '-' . $request->slug. '.' . $request->image_url->extension();
                $file_path = $request->image_url->storeAs('images/product_images',  $filename, 'public');
            } 
            // dd( $request->is_active);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
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
}
