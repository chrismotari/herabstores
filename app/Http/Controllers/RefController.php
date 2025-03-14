<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stringable;
use Illuminate\Support\Str;

class RefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('refs.index', [
            'refs' => Ref::all(),
            'users' => User::all(),
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
        $ref = Ref::create([
            'user_id' => $request->user_id,
            'description' => $request->description,
            'is_active' => $request->is_active,
            'expires_at' => Carbon::createFromFormat('m/d/Y', $request->expires_at)->format('Y-m-d'),
        ]);
        if ($ref) {
            flash()->success('Ref created successfully');
            return redirect()->route('refs.index');
        }else{          
            flash()->success('Error! Ref not created');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ref $ref)
    {
        return view('refs.show', ['selected_ref'=>$ref]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ref $ref)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ref $ref)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ref $ref)
    {
        if($ref->delete()){
            return back()->with('success', 'Ref has been deleted successfully');
        }
    }
}
