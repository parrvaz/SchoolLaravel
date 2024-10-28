<?php

namespace App\Http\Resources\Reports\Card;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'average'=>$this["average"],
            'scores' =>new CardCourseCollection($this['studentExam']) ?? null,


        ];
    }
}
