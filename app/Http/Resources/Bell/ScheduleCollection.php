<?php

namespace App\Http\Resources\Bell;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
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
                "id"=>$item->id,
                'classroom_id'=> $item->classroom_id,
                'classroom'=> $item->classroom->title,
                'course_id'=> $item->course_id,
                'course'=> $item->course->title,
                'bell_id'=>$item->bell_id,
                'day'=>$item->day,
            ];
        })];
    }
}
