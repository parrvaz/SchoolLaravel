<?php

namespace App\Http\Resources\Exam;

use App\Models\Classroom;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ScoreCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['data'=>$this->collection->map(function ($item){
            return[
                'score' =>$item->score,
                'date' => self::gToJ( $item->exam->date),
                'course_id' => $item->exam->course_id,
                'course' => $item->exam->course->name,
                'expected' => $item->exam->expected,
                'totalScore' => $item->exam->totalScore,
                'type' => new TypeExamResource( $item->exam) ,

            ];
        })];
    }
}
