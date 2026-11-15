<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;

/**
 * Storefront contact form. The inbox is in Filament (/admin/contacts).
 */
class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        Contact::create([
            ...$request->safe()->except('consented'),
            'consented' => $request->boolean('consented'),
            'source' => 'Website',
        ]);

        return redirect()->back()->with('success', "Your request was successful! You'll hear from us soon.");
    }
}
