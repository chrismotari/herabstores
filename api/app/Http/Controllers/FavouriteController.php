<?php

namespace App\Http\Controllers;

use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        try {
            $validatedData = $request->validate([
                'product_id' => [
                    'required',
                    'string',
                    'max:50',
                    'exists:products,id',
                    Rule::unique('favourites')->where(function ($query) use ($request) {
                        return $query->where('user_id', auth()->id());
                    }),
                ]
            ],[
                'product_id.unique' => 'Product already added to favourites',
            ]);
    
            Favourite::create([
                'user_id' => auth()->id(),
                'product_id' => $validatedData['product_id'],
            ]);
            return back()->with('success', 'Product added to favourites');
        }  catch (ValidationException $e) {
            flash()->error($e->validator->errors()->first());
            return redirect()->back()->withErrors($e->validator)->withInput();
        }catch (\Throwable $th) {
            flash()->error('An error occurred while adding product to favourites');
            return redirect()->back();

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Favourite $favourite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Favourite $favourite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Favourite $favourite)
    {
        //
    }
}
