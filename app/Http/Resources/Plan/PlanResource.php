<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Exam\ContentCollection;
use App\Http\Resources\Exam\StudentScoreCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'classroom_id' => $this->classroom_id,
            'title' => $this->title,
            'plan'=> new CoursePlanCollection($this->coursePlans)
        ];
    }
}
