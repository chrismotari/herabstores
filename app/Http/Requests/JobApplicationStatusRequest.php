<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Http\Requests\FormRequest;
use App\Models\JobApplicationStatus;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Validation\Rule;

class JobApplicationStatusRequest extends LaravelFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ($user = auth()->user()) ||
            ($user instanceof User);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->isMethod('POST')){
            return [
                "description" => ['nullable'],
                "name" => ['required', Rule::unique((new JobApplicationStatus)->getTable())],
            ];
        } else {
            return [
                "description" => ['nullable'],
                "name" => ['required', Rule::unique((new JobApplicationStatus)->getTable())->ignore($this->route('job_application_status')->id)],
            ];
        }
    }
}
