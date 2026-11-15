<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\RegisterRequest as WebRegisterRequest;

class RegisterRequest extends WebRegisterRequest
{
    public function rules(): array
    {
        return parent::rules() + [
            /** Send to receive a bearer token (mobile). Omit for a session cookie (web SPA). */
            'device_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
