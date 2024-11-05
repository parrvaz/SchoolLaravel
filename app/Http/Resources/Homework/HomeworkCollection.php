<?php

namespace App\Http\Resources\Homework;

use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function Laravel\Prompts\select;

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
                'description' => $item->description,
                'date' => self::gToJ($item->date),
                'course_id' => $item->course_id,
                'course' => $item->course->name,
                'score' => $item->score,
                'expected' => $item->expected,
                'isFinal' => (bool) $item->isFinal,
                'link' => $item->link,
                'classrooms' => $item->classrooms,
                'photos' => new FileCollection($item->photos),
                'voices' =>new FileCollection( $item->voices),
                'pdfs' =>new FileCollection( $item->pdfs),
            ];
        })->toArray();
    }
}
