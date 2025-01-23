<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Job;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Validation\Rule;

class JobRequest extends LaravelFormRequest
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
        if ($this->isMethod('POST')) {
            return [
                // "job_category_id" => ["required", 'uuid', "exists:job_categories,id"],
                // "profession_id" => ["nullable", 'uuid', "exists:professions,id"],
                // "contract_type_id" => ["required", 'uuid', "exists:contract_types,id"],
                // "company_id" => ["required", 'uuid', "exists:companies,id"],
                // "country_id" => ["required", 'uuid', "exists:countries,id"],
                "name" => ["required", "unique:jobs,name"],
                "about_job" => ["required",'max:1000'],
                "min_salary" => ["required","numeric"],
                "max_salary" => ["required","numeric"],
                "job_description" => ["required","array"],
                "qualifications" => ["required","array"],
                "responsibilities" => ["nullable","array"],
                // "tags" => [],
                "application_deadline" => ["nullable","date"],
                "flyer_url" => ["nullable","url:http,https"]
            ];
        } else {
            return [
                "job_category_id" => ["required", 'uuid', "exists:job_categories,id"],
                "profession_id" => ["nullable", 'uuid', "exists:professions,id"],
                "contract_type_id" => ["required", 'uuid', "exists:contract_types,id"],
                "company_id" => ["required", 'uuid', "exists:companies,id"],
                "country_id" => ["required", 'uuid', "exists:countries,id"],
                "name" => ["required", Rule::unique((new Job)->getTable())->ignore($this->route('job')->id)],
                "about_job" => ["required",'max:1000'],
                "min_salary" => ["required","numeric"],
                "max_salary" => ["required","numeric"],
                "job_description" => ["required","array"],
                "qualifications" => ["required","array"],
                "responsibilities" => ["nullable","array"],
                // "tags" => [],
                "application_deadline" => ['nullable',"date"],
                "flyer_url" => ["nullable","url:http,https"]
            ];
        }
        
        
    }
}
