<?php

namespace App\Http\Resources\Exam;

use App\Models\Classroom;
use App\Models\Course;
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
            'classroom' => $item->classroomTitle?? Classroom::find($this->classroom_id)->title,
            'date' => $this->date,
            'course_id' => $this->course_id,
            'course' => $item->courseName ?? Course::find( $this->course_id)->name,
            'expected' => $this->expected,
            'totalScore' => $this->totalScore,
            'isFinal' =>$this->status,
            'type' =>$this->type ,
            'isGeneral' =>$this->isGeneral,
            'contents'=>new ContentCollection($this->contents),
            'students'=>new StudentScoreCollection($this->students)
        ];
    }
}
