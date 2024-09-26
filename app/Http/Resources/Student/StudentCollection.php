<?php

namespace App\Http\Resources\Student;

use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentCollection extends ResourceCollection
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
                'id'=>$item->id,
                'firstName'=>$item->firstName,
                'lastName'=>$item->lastName,
                'name'=>$item->name,
                'nationalId'=>$item->nationalId,
                'classroom_id'=>$item->classroom_id,
                'classroom'=>$item->classroomTitle,
                'birthday'=> self::gToJ($item->birthday) ,
                'isOnlyChild'=> (bool)$item->onlyChild,
                'address'=>$item->address,
                'phone'=>$item->phone,
                'fatherPhone'=>$item->fatherPhone,
                'motherPhone'=>$item->motherPhone,
                'socialMediaID'=>$item->socialMediaID,
                'numberOfGlasses'=>$item->numberOfGlasses,
                'isLeftHand'=> (bool)$item->leftHand,
                'religion'=>$item->religion,
                'specialDisease'=>$item->specialDisease,
                "user_id"=> $item->user->id ?? null,
                "parent_id"=> $item->parentUser->id ?? null
            ];
        })];
    }
}
