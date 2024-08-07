<?php

namespace App\Http\Resources\Score;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'user_grade_id' => $this->id,
            'classroom_id' => $this->classroom_id,
            'date' => $this->date,
            'title' => $this->title,
            'courses'=>new CourseCollection($this->courses),
        ];
    }
}
