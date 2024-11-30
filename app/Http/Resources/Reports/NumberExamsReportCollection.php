<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Course\ContentCollection;
use App\Models\Course;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class NumberExamsReportCollection extends ResourceCollection
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
                'course_id'=> $item[0]->course_id ?? null,
                'course'=> Course::find($item[0]->course_id)->title ?? null,
                'minimum'=> $item->min("count") ?? null,
                'maximum'=> $item->max("count") ?? null,
                'average'=> round($item->average("count") , 2)  ?? null,
            ];
        })->toArray();
    }
}
