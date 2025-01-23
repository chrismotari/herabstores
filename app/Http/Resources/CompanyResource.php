<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "contact_person_name" => $this->contact_person_name,
            "description" => $this->description,
            "phone" => $this->phone,
            "email" => $this->email,
            "address" => $this->address,
            "city" => $this->city,
            "state" => $this->state,
            "zip_code" => $this->zip_code,
            "country" => $this->country,
            "social_handles" => $this->social_handles,
        ];
    }
}
