<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Exam\ContentCollection;
use App\Http\Resources\Exam\StudentScoreCollection;
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
            'tickValues' =>$this["tickValues"] ?? null,
            'tickFormat' =>$this["tickFormat"] ?? null,

        ];
    }
}
