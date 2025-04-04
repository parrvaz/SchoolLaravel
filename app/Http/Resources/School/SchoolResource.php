<?php

namespace App\Http\Resources\School;

use App\Http\Resources\Grade\SchoolGradeCollection;
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
            'wallet' => $this->wallet,
            'logo' => $this->logo ? url('storage/' . $this->logo) : null,
            'location' => $this->location,
            'phone' => $this->phone,
            'gender' => $this->gender == null? null : ($this->gender ? "female" : "male"),
            'postalCode' => $this->postalCode,
            'bankAccount' => $this->bankAccount,
            'website' => $this->website,
            'socialMedia' => $this->socialMedia,

            'grades' => new SchoolGradeCollection($this->grades),


        ];
    }
}
