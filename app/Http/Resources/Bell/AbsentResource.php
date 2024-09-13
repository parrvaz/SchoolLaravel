<?php

namespace App\Http\Resources\Bell;

use App\Http\Resources\Student\StudentShortCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsentResource extends JsonResource
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
            'user_id'=> $this->user_id,
            'user_name'=> $this->user->name,
            'date'=> $this->order,
            'bell_id'=> $this->startTime,
            'classroom_id'=> $this->endTime,
            "students"=> new StudentShortCollection($this->students)
        ];
    }
}
