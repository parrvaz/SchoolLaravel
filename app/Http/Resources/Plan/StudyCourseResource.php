<?php

namespace App\Http\Resources\Plan;

use App\Http\Resources\Exam\ContentCollection;
use App\Http\Resources\Exam\StudentScoreCollection;
use App\Traits\FilterTrait;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyCourseResource extends JsonResource
{
    use ServiceTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'title' => $this->course->name,
            'course_id' =>$this->course_id,
            "date"=>  self::Gtoj($this->date),
            "time"=>  $this->time,
            "isFix"=>false,
        ];
    }
}
