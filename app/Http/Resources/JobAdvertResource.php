<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobAdvertResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id, 
            "job_category" => $this->job_category,
            "profession" => $this->profession,
            "contract_type" => $this->contract_type,
            "user" => $this->user,
            "company" => $this->company,
            "country" => $this->country,
            "city" => $this->city,
            "name" => $this->name,
            "min_salary" => $this->min_salary,
            "max_salary" => $this->max_salary,
            "about_job" => $this->about_job,
            // "job_description" => $this->job_description,
            "responsibilities" => $this->responsibilities,
            "qualifications" => $this->qualifications,
            "tags" => $this->tags,
            "application_deadline" => $this->application_deadline,
            "application_deadline" => $this->application_deadline,
            "flyer_url" => $this->flyer_url,
            // "salary_frequency" => $this->salary_frequecy,
            // "experience_level" => $this->experience_level,
            // "location" => ($this->city.", ".$this->county->name),
            // // //
        ];
    }
}
