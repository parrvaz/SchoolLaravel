<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id ?? null,
            'firstName'=>$this->firstName ?? null,
            'lastName'=>$this->lastName ?? null,
            'nationalId'=>$this->nationalId ?? null,
            'degree'=>$this->degree ?? null,
            'personalId'=>$this->personalId ?? null,
            'phone'=>$this->phone ?? null,
            'user_grade_id'=>$this->school_grade_id ?? null,
            'isAssistant'=> (bool) ($this->isAssistant ?? null) ?? null,
            "user_id"=> $this->user->id ?? null

        ];
    }
}
