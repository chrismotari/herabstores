<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Basket;

class BasketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $basketId = $this->route('basket')?->id;
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'slug' => ['required', 'string', 'unique:'.Basket::class] ,
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'is_active' => 'boolean',
                'effective_from' => 'required|date',
                'effective_to' => 'required|date|after_or_equal:effective_from',
            ];
        } else {
            return [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'slug' => 'sometimes|required|string|unique:baskets,slug,' . $this->route('basket')->id,
                'price' => 'sometimes|required|numeric|min:0',
                'stock' => 'sometimes|required|integer|min:0',
                'is_active' => 'sometimes|boolean',
                'effective_from' => 'sometimes|required|date',
                'effective_to' => 'sometimes|required|date|after_or_equal:effective_from',
            ];
        }
    }
}
