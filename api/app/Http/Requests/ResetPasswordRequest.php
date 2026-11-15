<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use App\Http\Requests\FormRequest;

class ResetPasswordRequest extends LaravelFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !($user = auth()->user()) ||
            !($user instanceof User);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'exists:users,email',
                'email'
            ],
            'token' => [
                'required'
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed',
            ]
        ];
    }
}
