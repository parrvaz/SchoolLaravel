<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScoreHomeworkResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'date' => self::gToJ($this->date),
            'course_id' => $this->course_id,
            'course' => $this->course->name,
            'score' => $this->score,
            'expected' => $this->expected,
            "isFinal"=>(bool) $this->isFinal,

            'submitted'=> new ScoreListHomeworkCollection($this->students),
            'notSubmitted'=> new ScoreNotSubmittedListHomeworkCollection($this->allStudents),

        ];
    }
}
