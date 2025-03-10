<?php

namespace App\Http\Resources\Homework;

use App\Http\Resources\Classroom\ClassroomShortCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentHomeworkResource extends JsonResource
{
    use ServiceTrait;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $stdHomework = $this->studentHomework(auth()->user()->modelHasRole->idInRole)->first();
        return [
            'id' =>$this->id,
            'title' => $this->title,
            'description' => $this->description,
            'date' => self::gToJ($this->date),
            'course_id' => $this->course_id,
            'course' => $this->course->name,
            'score' => $this->score,
            'link' => $this->link,
            'voices' =>new FileCollection( $this->voices),
            'files' =>new FileCollection( $this->files),

//            'photos' => new FileCollection($this->photos),
//            'pdfs' =>new FileCollection( $this->pdfs),

            'studentHomework_id'=> $stdHomework->id ?? null,
            'solution'=> $stdHomework!= null ? url('storage/' . $stdHomework->solution) : null,
            "note"=> $stdHomework!=null ? $stdHomework->note : null,

        ];
    }
}
