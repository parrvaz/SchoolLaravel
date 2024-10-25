<?php

namespace App\Http\Resources\Reports\Card;

use App\Http\Resources\Course\ContentCollection;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CardCourseCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item){
            return[
                'course_id'=> $item->course_id ?? null,
                'course'=> Course::find($item->course_id)->name,
                'factor'=> $item->factor?? null,
                'score'=> $item->score?? null,
            ];
        })->toArray();
    }
}
