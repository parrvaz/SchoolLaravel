<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Morilog\Jalali\Jalalian;

class StudyCourseCollection extends ResourceCollection
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
                'id' =>$item['id'],
                'title' => $item['title'],
                'course_id' =>$item['course_id'],
                "date"=> $item['date'],
                "isFix"=>$item['isFix'],
            ];
        })->toArray();
    }


}
