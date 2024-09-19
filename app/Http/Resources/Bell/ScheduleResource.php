<?php

namespace App\Http\Resources\Bell;

use App\Http\Resources\Student\StudentShortCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            'course_id'=> $this->course_id,
            'course'=> $this->course->title,
            'bell_id'=>$this->bell_id,
            'day'=>$this->day,
        ];
    }
}
