<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Student\StudentShortCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                'id' =>$item->user->id,
                'name' => $item->user->name,
                'role'=>$item->user->getRoleNames()->first(),
                'role_id'=>$item->user->modelHasRole->idInRole ?? null
            ];
        })->toArray();
    }
}
