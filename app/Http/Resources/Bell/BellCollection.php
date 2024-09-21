<?php

namespace App\Http\Resources\Bell;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BellCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['data'=>$this->collection->map(function ($item){
            return[
                'id'=> $item->id,
                'order'=> $item->order,
                'startTime'=> \Carbon\Carbon::createFromFormat('H:i:s',$item->startTime)->format('H:i'),
                'endTime'=> \Carbon\Carbon::createFromFormat('H:i:s',$item->endTime)->format('H:i'),
            ];
        })];
    }
}
