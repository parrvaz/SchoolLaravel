<?php

namespace App\Http\Resources\Grade;

use App\Repositories\BankRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuItemCollection extends ResourceCollection
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
                'title'=>$item->label,
//                'sub'=> $item->hasSub ? new MenuItemCollection($item->subs) : null,
            ];
        })];
    }
}
