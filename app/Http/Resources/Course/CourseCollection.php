<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseCollection extends ResourceCollection
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
                'id'=> $item->id,
                'name'=> $item->name,
                'grade_id'=> $item->grade_id,
                'factor'=> $item->factor,
                'type'=> $item->type ? "تخصصی" : "عمومی",
                'contentCount'=> $item->contents->groupBy("content")->count(),
                'contents'=> new ContentCollection($item->contents),
            ];
        })];
    }
}
