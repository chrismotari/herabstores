<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            "name" => $this->job_application,
            "job_application" => $this->job_application,
            "is_selected" => $this->is_selected,
            "description" => $this->description,
            "appointment_at" => $this->appointment_at,
            "created_by" => $this->created_by,
        ];
    }
}
