<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'logo' => $this->logo,
            'location' => $this->location,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'postalCode' => $this->postalCode,
            'bankAccount' => $this->bankAccount,
            'website' => $this->website,
            'socialMedia' => $this->socialMedia,
        ];
    }
}
