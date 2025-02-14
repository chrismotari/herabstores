<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "fname" => $this->fname,
            "mname" => $this->mname,
            "lname" => $this->lname,
            "name" => $this->name,
            "phone" => $this->phone,
            "alt_phone" => $this->alt_phone,
            "email" => $this->email,
            "alt_email" => $this->alt_email,
            "address" => $this->address,
            "city" => $this->city,
            "estate" => $this->estate,
            "country" => $this->country,
            "photo_url" => $this->photo_url,
            "dob" => $this->dob
        ];
    }
}
