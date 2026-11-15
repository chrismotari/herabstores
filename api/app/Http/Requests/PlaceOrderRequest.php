<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Checkout (pay on delivery). Guests and logged-in customers both use this.
 */
class PlaceOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'alt_phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'delivery_zone_id' => ['required', 'uuid', 'exists:delivery_zones,id'],
            'estate_id' => ['nullable', 'uuid', 'exists:estates,id'],
            'ref_id' => ['nullable', 'uuid', 'exists:refs,id'],
            'message' => ['nullable', 'string', 'max:255'],
            'consented' => ['nullable'],
            'accept_terms' => ['accepted'],
        ];
    }
}
