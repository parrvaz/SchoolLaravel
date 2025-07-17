<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreListAllHomeworkCollection extends ResourceCollection
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
            $student = $item->student;
            $diffDay = null;
            if ($student != null)
                $diffDay = $item->updated_at != null ? $item->updated_at->diffInDays($item->homework->date) : null;

            return[
                'id' =>$item->id,
                "student_id"=>$student->id ?? $item->id,
                "name"=>$student->name ?? $item->name,
                "firstName"=>$student->firstName ?? $item->firstName,
                "lastName"=>$student->lastName ?? $item->lastName,
                "classroom"=>$student->classroom->title ?? $item->classroom->title,
                "classroom_id"=>$student->classroom_id ?? $item->classroom_id,

                'score'=> $item->score ?? null,
                "feedback"=>  $student==null ? null :  $this->scoreFeedback($item->score,$item->homework->score,$item->homework->expected),
                'status'=> $diffDay!=null ? ( $diffDay > -1 ? "okSubmitted" : (int) $diffDay * -1) : "notSubmitted",

            ];
        })->toArray();
    }
}
