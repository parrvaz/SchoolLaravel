<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentHomeworkCollection extends ResourceCollection
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
                'modifiedDate' => self::gToJ( $item->updated_at),
                'date' => self::gToJ($item->date),
                'score'=> $item->score,
                'status'=> $item->status,

            ];
        })->toArray();
    }
}
