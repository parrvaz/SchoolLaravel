<?php

namespace App\Http\Resources\Score;

use App\Models\Classroom;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AllExamCollection extends ResourceCollection
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
                'id' =>$item->id,
                'title' =>$item->title,
                'classroom' => $item->classroom,
                'type' => $item->type,
                'tbl' => $item->tbl,
                'date' => ServiceTrait::gToJ( $item->date),
            ];
        })];
    }
}
