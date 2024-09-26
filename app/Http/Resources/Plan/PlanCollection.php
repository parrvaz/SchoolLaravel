<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Student\StudentShortCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
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
                'classroom_id' => $item->classroom_id,
                'classroom' => $item->classroom->title ?? null,
                'title' => $item->title,
                'students'=>new StudentShortCollection($item->students)

            ];
        })->toArray();
    }
}
