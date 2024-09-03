<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContentCollection extends ResourceCollection
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
                'id'=> $item->id,
                'course_id'=> $item->course_id,
                'season'=> $item->season,
                'content'=> $item->content,
                'pageStart'=> $item->pageStart,
                'pageEnd'=> $item->pageEnd,
                'priority'=> $item->priority,
            ];
        })->toArray();
    }
}
