<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasketRequest;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $baskets = Basket::all();
        return view('baskets.index', ['baskets'=>$baskets]);
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
    public function store(BasketRequest $request)
    {
        // dd($request->all());

        $filename = null;
            $file_path = null;

        if ($request->hasFile('image')) {
            $filename = time(). '-' . $request->slug. '.' . $request->image->extension();
            $file_path = $request->image->storeAs('images/basket_images',  $filename, 'public');
        }

        $basket = Basket::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->is_active,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to
        ]);

        $basket->image = $file_path;
        $basket->save();

        return redirect()->route('baskets.index')->with('success', 'Basket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Basket $basket)
    {
        return view('baskets.show', ['selected_basket'=>$basket]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Basket $basket)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BasketRequest $request, Basket $basket)
    {
        $filename = null;
        $file_path = null;

        if ($request->hasFile('image')) {
            $filename = time(). '-' . $request->slug. '.' . $request->image->extension();
            $file_path = $request->image->storeAs('images/basket_images',  $filename, 'public');
        }

        $basket->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->is_active,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ]);

        if ($file_path) {
            $basket->image = $file_path;
            $basket->save();
        }

        return redirect()->route('baskets.index')->with('success', 'Basket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Basket $basket)
    {
        if ($basket->image && Storage::disk('public')->exists($basket->image)) {
            Storage::disk('public')->delete($basket->image);
        }
        $basket->delete();
        return redirect()->route('baskets.index')->with('success', 'Basket deleted successfully.');
    }
}
