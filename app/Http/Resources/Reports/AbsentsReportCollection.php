<?php

namespace App\Http\Resources\Reports;

use App\Http\Resources\Course\ContentCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AbsentsReportCollection extends ResourceCollection
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
                'name'=> $item->firstName ." ".$item->lastName ?? null,
                'classroom'=> $item->classroomTitle ?? null,
                'absents'=> $item->number ?? null,
                'allBells'=> $item->total ?? null,
                'percent'=> $item->percent ?? null,
                'status'=> $item->rank ?? null,
            ];
        })->toArray();
    }
}
