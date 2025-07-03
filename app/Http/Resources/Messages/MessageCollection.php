<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Auth\UserCollection;
use App\Http\Resources\Course\ContentCollection;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MessageCollection extends ResourceCollection
{
    use ServiceTrait;
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
                'date'=> self::gToJ($item->created_at),
                'subject'=> $item->subject,
                'body'=> $item->body,
                'type'=> $item->type==2 ? "sms":"system",
                "recipients"=> new UserCollection($item->recipients)
            ];
        })];
    }
}
