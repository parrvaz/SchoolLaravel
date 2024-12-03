<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreNotSubmittedListHomeworkCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'id' =>null,
                "student_id"=>$item->id,
                "name"=>$item->name,
                "firstName"=>$item->firstName,
                "lastName"=>$item->lastName,
                "classroom"=>$item->classroom->title,
                "classroom_id"=>$item->classroom_id,

                'score'=> null,
                "feedback"=> null,
                'status'=> "notSubmitted",

            ];
        })->toArray();
    }
}
