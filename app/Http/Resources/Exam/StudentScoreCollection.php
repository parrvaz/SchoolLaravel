<?php

namespace App\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use function PHPUnit\Framework\isNull;

class StudentScoreCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'id' =>$item->id,
                'student_id' => $item->student_id,
                'name'=> $item->student->name,
                'score' => $item->score,
                'isPresent' => is_null($item->isPresent) ? null : (bool) $item->isPresent,
            ];
        })->toArray();
    }
}
