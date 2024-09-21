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
            'id'=>$this->id,
            'firstName'=>$this->firstName,
            'lastName'=>$this->lastName,
            'nationalId'=>$this->nationalId,
            'degree'=>$this->degree,
            'personalId'=>$this->personalId,
            'phone'=>$this->phone,
            'user_grade_id'=>$this->user_grade_id,
            'isAssistant'=> (bool) $this->isAssistant,
            "user_id"=> $this->user->id

        ];
    }
}
