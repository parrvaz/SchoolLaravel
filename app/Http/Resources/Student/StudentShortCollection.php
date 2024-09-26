<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentShortCollection extends ResourceCollection
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
                'id'=>$item->id,
                'firstName'=>$item->firstName,
                'lastName'=>$item->lastName,
                'name'=>$item->name,
                'nationalId'=>$item->nationalId,
                'classroom_id'=>$item->classroom_id,
                'classroom'=>$item->classroomTitle,
                'phone'=>$item->phone,
                'fatherPhone'=>$item->fatherPhone,
                'motherPhone'=>$item->motherPhone,

            ];
        })->toArray();
    }
}
