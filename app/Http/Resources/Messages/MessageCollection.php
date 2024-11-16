<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Auth\UserCollection;
use App\Http\Resources\Course\ContentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
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
                'subject'=> $item->subject,
                'body'=> $item->body,
                'type'=> $item->type==2 ? "sms":"system",
                "recipients"=> new UserCollection($item->recipients)
            ];
        })];
    }
}
