<?php

namespace App\Http\Resources\Exam;

use App\Models\Classroom;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCollection extends ResourceCollection
{
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
                'user_grade_id' => $item->id,
                'classroom_id' => $item->classroom_id,
                'classroom' => $item->classroomTitle?? Classroom::find($item->classroom_id)->title,
                'date' => ServiceTrait::gToJ( $item->date),
                'course_id' => $item->course_id,
                'course' => $item->courseName ?? Course::find( $item->course_id)->name,
                'expected' => $item->expected,
                'totalScore' => $item->totalScore,
                'status' => $item->status,
                'type' =>$this->type ,
                'isGeneral' =>$this->isGeneral,
            ];
        })];
    }
}
