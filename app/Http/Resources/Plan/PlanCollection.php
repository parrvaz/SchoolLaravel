<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
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
                'user_grade_id' => $item->id,
                'classroom_id' => $item->classroom_id,
                'date' => $item->date,
                'course_id' => $item->course_id,
                'minutes' => $item->minutes,
            ];
        })->toArray();
    }
}