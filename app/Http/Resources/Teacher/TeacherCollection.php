<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TeacherCollection extends ResourceCollection
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
                'id'=>$item->id,
                'firstName'=>$item->firstName,
                'lastName'=>$item->lastName,
                'name'=>$item->name,
                'nationalId'=>$item->nationalId,
                'degree'=>$item->degree,
                'personalId'=>$item->personalId,
                'phone'=>$item->phone,
                'user_grade_id'=>$item->user_grade_id,
                'isAssistant'=> (bool) $item->isAssistant,
                "user_id"=> $item->user->id ?? null

            ];
        })];
    }
}
