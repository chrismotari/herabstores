<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobApplicationResource extends JsonResource
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
            "job" => $this->job,
            "resume" => $this->resume,
            "status" => $this->status,
            "applicant" => $this->applicant,
            "cover_letter" => $this->cover_letter,
            "additional_info" => $this->additional_info,
            "progress_tracker" => $this->progress_tracker,
            "is_draft" => $this->is_draft,
        ];
    }
}
