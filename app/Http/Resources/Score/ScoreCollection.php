<?php

namespace App\Http\Resources\Score;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreCollection extends ResourceCollection
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
                'date' => $item->date,
                'course_id' => $item->course_id,
                'expected' => $item->expected,
                'totalScore' => $item->totalScore,
            ];
        })];
    }
}
