<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Http\Requests\FormRequest;
use App\Models\JobApplication;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class JobApplicationRequest extends LaravelFormRequest
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
        return [
            "job_id" => ['nullable', 'exists:jobs,id'],
            "applicant_id" => ['required', 'exists:users,id'],
            "resume_id" => ['required','exists:resumes,id'],
            "cover_letter" => ['nullable','string'],
            "additional_info" => ['nullable','json'],
            "status_id" => ['nullable', 'exists:job_application_statuses,id'],
            "progress_tracker" => ['nullable', 'json'],
            "is_draft" => ['nullable', 'boolean'],
        ];   
    }
}