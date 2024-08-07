<?php

namespace App\Http\Resources\Score;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestCollection extends ResourceCollection
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
                'id' =>$item->id,
                'title' => $item->title,
                'date' => $item->date,
                'classroom_id' => $item->classroom_id,
                'user_grade_id' => $item->user_grade_id,
            ];
        })->toArray();
    }
}
