<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
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
            'course_id' => $this->course_id,
            'expected' => $this->expected,
            'totalScore' => $this->totalScore,
            'contents'=>new ContentCollection($this->contents),
            'students'=>new StudentScoreCollection($this->students)
        ];
    }
}
