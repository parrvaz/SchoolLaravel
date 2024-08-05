<?php

namespace App\Http\Resources\Grades;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'grade_id' => $this->grade_id,
        ];
    }
}
