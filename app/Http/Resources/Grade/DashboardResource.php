<?php

namespace App\Http\Resources\Grade;

use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Reports\ExamCountCollection;
use App\Http\Resources\Score\ContentCollection;
use App\Http\Resources\Score\StudentScoreCollection;
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
//            'exam' =>new ExamCountCollection($this["exam"]) ?? null,
//            'classScore' =>new ExamCountCollection($this["classScore"]?? []) ,
//            'user' =>new UserResource(auth()->user()) ,
//            'tickValues' =>$this["tickValues"] ?? null,
//            'tickFormat' =>$this["tickFormat"] ?? null,

        ];
    }
}
