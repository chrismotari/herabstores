<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            /** Send to receive a bearer token (mobile). Omit for a session cookie (web SPA). */
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
