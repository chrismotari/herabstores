<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('estates.index', [
            'estates' => Estate::all()
        ]);
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
        // dd($request->all());
        $estate = Estate::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
            'delivery_fees' => $request->delivery_fees,
        ]);
        if ($estate) {
            flash()->success('Estate created successfully');
            return redirect()->route('estates.index');
        }else{
            flash()->success('Error! Estate not created');
            return redirect()->back();
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Estate $estate)
    {
        return view('estates.show', [
            'selected_estate' => $estate
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estate $estate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estate $estate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estate $estate)
    {
        if ($estate->delete()) {
            flash('Estate deleted successfully')->success();
            return redirect()->route('estates.index');
        }
    }
}
