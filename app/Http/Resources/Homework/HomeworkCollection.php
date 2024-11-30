<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HomeworkCollection extends ResourceCollection
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
                'id' =>$item->id,
                'title' => $item->title,
                'course_id' => $item->course_id,
                'course' => $item->course->name,
                'classrooms' =>new ClassroomShortCollection($item->classrooms),
                'modifiedDate' => self::gToJ( $item->updated_at),
                'date' => self::gToJ($item->date),
                'totalStdNumber'=> $item->classrooms->reduce(function ($carry, $classroom) {
                    return $carry + $classroom->students->count();
                }, 0),
                'submitStdNumber'=> $item->students->filter(function ($e) {
                    return !is_null($e->note) || !is_null($e->solution);
                })->count(),
                'scoredNumber'=>$item->students->whereNotNull("score")->count(),
                'isFinal' => (bool) $item->isFinal,

            ];
        })->toArray();
    }
}
