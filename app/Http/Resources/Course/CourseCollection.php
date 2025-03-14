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
                "isUser"=> $item->school_grade_id!=null,
                "field_id"=> $item->field_id,
                "field"=> config("constant.fields.".$item->field_id) ?? null,
                'contentCount'=> $item->contents->groupBy("content")->count(),
                'contents'=> new ContentCollection($item->contents),
            ];
        })];
    }
}
