<?php

namespace App\Http\Resources\Grade;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

//            'filterDate'=> new ExamCreateResource($this),
//            'examCount'=> new AllCountResource($this)
//
//            'exam' =>new ProgressCollection($this["exam"]) ?? null,
//            'classScore' =>new ProgressCollection($this["classScore"]?? []) ,
//            'user' =>new UserResource(auth()->user()) ,
//            'tickValues' =>$this["tickValues"] ?? null,
//            'tickFormat' =>$this["tickFormat"] ?? null,

        ];
    }
}
