<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', ['categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'is_active' => 'nullable'
        ]);

        $file_path = null;
        $slug = Str::slug($request->name, '-');

        if ($request->hasFile('image_url')) {
            $filename = time(). '-' . $request->slug. '.' . $request->image_url->extension();
            $file_path = $request->image_url->storeAs('images/category_images',  $filename, 'public');
        } 

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $slug,
            'image_url' => $file_path,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Category created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('categories.show', ['selected_category'=>$category]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'is_active' => 'nullable'
        ]);
        $filename = $category->image_url; // Keep existing image
        $file_path = $category->image_url;

        if ($request->hasFile('image_url')) {
            // Delete old image if exists
            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }

            $filename = time() . '-' . $request->slug . '.' . $request->image_url->extension();
            $file_path = $request->image_url->storeAs('images/category_images', $filename, 'public');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name, '-'),
            'image_url' => $file_path,
            'is_active' => $request->is_active
        ]);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
            Storage::disk('public')->delete($category->image_url);
        }
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
