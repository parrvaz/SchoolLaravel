<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'id' =>$item->id,
                'course_id' => $item->course_id,
                'course_name' => $item->course->name,
                'expected' => $item->expected,
                'average' => $item->average,
                'contents'=>new ContentCollection($item->contents),
                'students'=>new StudentScoreCollection($item->students),
//                'students'=>new StudentScoreCollection($item->students),

            ];
        })->toArray();
    }
}
