<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Validation\Rules;

class UserRequest extends LaravelFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return $this->id;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dump($this->id);
        if($this->isMethod('POST')){
            return [
                
                'photo_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'resume_url' => 'mimes:pdf|max:10240',

                'fname' => ['required', 'string', 'max:255'],
                'mname' => ['nullable', 'string', 'max:255'],
                'lname' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20', 'unique:'.User::class],
                'alt_phone' => ['nullable', 'string', 'max:20', 'unique:'.User::class],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'alt_email' => ['nullable', 'string', 'email', 'max:255', 'unique:'.User::class],
                'dob' => ['nullable', 'date'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:10'],
                'photo_url' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'],
                'resume_url' => ['nullable', 'mimes:pdf, doc, docx', 'max:10240'],
                'country_id' => ['required', 'uuid', 'exists:countries,id'],
                'state_id' => ['nullable', 'uuid', 'exists:states,id'],
                'job_category_id' => ['nullable', 'uuid', 'exists:job_categories,id'],
                'profession_id' => ['nullable', 'uuid', 'exists:professions,id'],
                'contract_type_id' => ['nullable', 'uuid', 'exists:contract_types,id'],
            ];
        } else{
            return [
                "fname" => ['required'],
                "mname" => ['required'],
                "lname" => ['required'],
                "phone" => ["required","unique:users,phone,".$this->route('user')->id],
                "alt_phone" => ["unique:users,alt_phone,".$this->route('user')->id],
                "email" => ["required","email","unique:users,email,".$this->route('user')->id],
                "alt_email" => ["email","unique:users,alt_email,".$this->route('user')->id],
                "dob" => ['nullable','date'],
                'photo_url' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'resume_url' => 'mimes:pdf|max:10240',
            ];
        }
    }
}
