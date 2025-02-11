<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Flasher\Laravel\Facade as Flasher;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('products.index', [
            'products' => Product::all()
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

            if ($request->hasFile('image_url')) {
                $filename = time(). '-' . $request->slug. '.' . $request->image_url->extension();
                $request->image_url->storeAs('public/images/product_images',  $filename);
            } 

           $product = Product::create([
               'name' => $request->name,
               'description' => $request->description,
               'slug' => $request->slug,
               'price' => $request->price,
               'stock' => $request->stock,
               'image_url' => $filename,
               'is_active' => $request->is_active ?? true,
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
}
