<?php

namespace App\Http\Resources\Classroom;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassroomResource extends JsonResource
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
            'title'=>$this->title,
            'number'=>$this->number,
            'floor'=>$this->floor,
            'user_grade_id'=>$this->school_grade_id,
            'field_id'=>$this->field_id,
        ];
    }
}
