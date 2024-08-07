<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Score\ContentCollection;
use App\Http\Resources\Score\StudentScoreCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentPlanResource extends JsonResource
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
            'student_id' => $this->student_id,
            'date' => $this->date,
            'course_id' => $this->course_id,
            'minutes' => $this->minutes,
        ];
    }
}
