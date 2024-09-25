<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CoursePlanCollection extends ResourceCollection
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
                'title' => $item->course->name,
                'course_id' => $item->course_id,
                'day' =>  config("constant.day.$item->day") ,
                'start' => \Carbon\Carbon::createFromFormat('H:i:s', $item->start)->format('H:i'),
                'end' => \Carbon\Carbon::createFromFormat('H:i:s', $item->end)->format('H:i'),
            ];
        })->toArray();
    }
}
