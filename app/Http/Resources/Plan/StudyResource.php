<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Exam\ContentCollection;
use App\Http\Resources\Exam\StudentScoreCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'title' => $this->title,
            'field_id' => $this->classroom->field_id ?? null,
            'field' => $this->classroom->field->title ?? null,
            'plan'=> new StudyCourseCollection($this->allItems)
        ];
    }
}
