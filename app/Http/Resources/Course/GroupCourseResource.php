<?php

namespace App\Http\Resources\Course;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $courseIds = explode(",",$this->resource);

        return collect($courseIds)->map(function ($item){
            return[
                'id'=> $item,
                'name'=> Course::find($item)->title,
            ];
        })->toArray();
    }
}
