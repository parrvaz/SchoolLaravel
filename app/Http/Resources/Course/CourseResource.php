<?php

namespace App\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'grade_id'=> $this->grade_id,
            'factor'=> $this->factor,
            'type'=> $this->type ? "تخصصی" : "عمومی",
            'contentCount'=> $this->contents->groupBy("content")->count(),
            'contents'=> new ContentCollection($this->contents),
        ];
    }
}
