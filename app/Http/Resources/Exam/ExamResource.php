<?php

namespace App\Http\Resources\Exam;

use App\Models\Classroom;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    use ServiceTrait;

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
            'date' =>  self::gToJ($this->date),
            'course_id' => $this->course_id,
            'course' => $item->courseName ?? Course::find( $this->course_id)->name,
            'expected' => $this->expected,
            'totalScore' => $this->totalScore,
            'isFinal' =>(bool) $this->status,
            'type' => new TypeExamResource( $this) ,
            'isGeneral' =>(bool) $this->isGeneral,
            'contents'=>new ContentCollection($this->contents),
            'students'=>new StudentScoreCollection($this->students)
        ];
    }
}
