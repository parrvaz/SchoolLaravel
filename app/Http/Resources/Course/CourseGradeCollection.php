<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseGradeCollection extends ResourceCollection
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
                'id'=> $item->id,
                'name'=> $item->name,
                'user_grade_id'=> $item->school_grade_id,

            ];
        })];
    }
}
