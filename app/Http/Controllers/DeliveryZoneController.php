<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('delivery_zones.index', [
            'delivery_zones' => DeliveryZone::all()
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
        $delivery_zone = DeliveryZone::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description,
            'delivery_fees' => $request->delivery_fees,
        ]);
        if ($delivery_zone) {
            flash()->success('Delivery zone created successfully');
            return redirect()->route('delivery_zones.index');
        }else{
            flash()->success('Error! Delivery zone not created');
            return redirect()->back();
        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryZone $delivery_zone)
    {
        return view('delivery_zones.show', [
            'selected_delivery_zone' => $delivery_zone
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeliveryZone $delivery_zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeliveryZone $delivery_zone)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryZone $delivery_zone)
    {
        if ($delivery_zone->delete()) {
            flash('Delivery zone deleted successfully')->success();
            return redirect()->route('delivery_zones.index');
        }
    }
}

