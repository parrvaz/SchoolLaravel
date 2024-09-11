<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Score\ContentCollection;
use App\Http\Resources\Score\StudentScoreCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'exam' =>new ExamCountCollection($this["exam"]) ?? null,
            'classScore' =>new ExamCountCollection($this["classScore"]?? []) ,
            'user' =>new UserResource(auth()->user()) ,
            'listItems' =>new ListItemsResource($this['userGrade']) ,
            'tickValues' =>$this["tickValues"] ?? null,
            'tickFormat' =>$this["tickFormat"] ?? null,

        ];
    }
}
