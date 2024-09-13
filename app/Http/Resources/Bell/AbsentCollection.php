<?php

namespace App\Http\Resources\Bell;

use App\Http\Resources\Student\StudentShortCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbsentCollection extends ResourceCollection
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
                'user_id'=> $item->user_id,
                'user_name'=> $item->user->name,
                'date'=> $item->order,
                'bell_id'=> $item->startTime,
                'classroom_id'=> $item->endTime,
                "students"=> new StudentShortCollection($item->students)
            ];
        })];
    }
}
