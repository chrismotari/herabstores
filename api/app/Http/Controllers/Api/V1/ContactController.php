<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Send a message through the contact form.
     *
     * @unauthenticated
     */
    public function store(ContactRequest $request): JsonResponse
    {
        Contact::create([
            ...$request->safe()->except('consented'),
            'consented' => $request->boolean('consented'),
            'source' => 'Website',
        ]);

        return response()->json(['message' => "Thanks! You'll hear from us soon."], 201);
    }
}
