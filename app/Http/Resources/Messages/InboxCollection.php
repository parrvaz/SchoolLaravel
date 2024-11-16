<?php

namespace App\Http\Resources\Messages;

use App\Http\Resources\Course\ContentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InboxCollection extends ResourceCollection
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
                'sender_id'=> $item->message->user_id,
                'sender'=> $item->message->sender->name,
                'subject'=> $item->message->subject,
                'body'=> $item->message->body,
                'type'=> $item->message->type ==2 ? "sms":"system",
                'isRead'=> (bool) $item->isRead,
            ];
        })];
    }
}
