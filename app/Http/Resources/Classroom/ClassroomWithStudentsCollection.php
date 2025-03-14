<?php

namespace App\Http\Resources\Classroom;

use App\Http\Resources\Student\StudentCollection;
use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomWithStudentsCollection extends ResourceCollection
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
                'id'=>$item->id,
                'title'=>$item->title,
                'number'=>$item->number,
                'floor'=>$item->floor,
                'user_grade_id'=>$item->school_grade_id,
                'field_id'=>$item->field_id,
                'field'=>$item->fieldTitle,
                'students'=>  new StudentCollection($item->students),
            ];
        })];
    }
}
