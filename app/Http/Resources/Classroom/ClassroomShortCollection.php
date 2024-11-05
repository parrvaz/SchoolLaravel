<?php

namespace App\Http\Resources\Classroom;

use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassroomShortCollection extends ResourceCollection
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
                'id'=>$item->id,
                'title'=>$item->title,
            ];
        })->toArray();
    }
}
