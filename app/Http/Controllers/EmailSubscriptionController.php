<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscription;
use Illuminate\Http\Request;

class EmailSubscriptionController extends Controller
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
            $request->validate([
                'email' => ['required', 'email', 'unique:email_subscriptions,email'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', 'Sorry, an issue occured, try again later.');
        }
        EmailSubscription::create([
            'email' => $request->email,
        ]);
        return redirect()->back()->with('success', 'You have successfully subscribed to our newsletter.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailSubscription $emailSubscription)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailSubscription $emailSubscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailSubscription $emailSubscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailSubscription $emailSubscription)
    {
        //
    }
}
