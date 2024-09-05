<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Course\ContentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCountCollection extends ResourceCollection
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
                'title'=> $item->title,
                'exam_count'=> $item->exam_count,
            ];
        })];
    }
}
