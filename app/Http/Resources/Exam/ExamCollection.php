<?php

namespace App\Http\Resources\Exam;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Models\Classroom;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['data'=>$this->collection->map(function ($item){
            return[
                'id' =>$item->id,
                'user_grade_id' => $item->school_grade_id,
                'classroom_id' => $item->classroom_id ?? null,
                'classroom' => $item->classroomTitle?? Classroom::find($item->classroom_id)->title ?? null,
                'date' => self::gToJ( $item->date),
                'modifiedDate' => self::gToJ( $item->updated_at),
                'course_id' => $item->course_id,
                'course' => $item->courseName ?? Course::find( $item->course_id)->name,
                'expected' => $item->expected,
                'totalScore' => $item->totalScore,
                'isFinal' => (bool)$item->status,
                'type' => new TypeExamResource( $item) ,
                'isGeneral' => (bool)$item->isGeneral,
                'contents'=>new ContentCollection($item->contents),
                'classrooms'=>new ClassroomShortCollection($item->classrooms),

            ];
        })];
    }
}
