<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Course\ContentCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCountCollection extends ResourceCollection
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
                'id'=> $item->id ?? null,
                'title'=> $item->title?? null,
                'count'=> $item->count?? null,
                'date'=> self::gToJ( $item->date)?? null,
                'score'=> $item->score?? null,
                'totalScore'=> $item->totalScore?? null,
                'expected'=> $item->expected?? null,
            ];
        })->toArray();
    }
}
