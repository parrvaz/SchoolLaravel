<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'firstName'=>$this->firstName,
            'lastName'=>$this->lastName,
            'nationalId'=>$this->nationalId,
            'classroom_id'=>$this->classroom_id,
            'birthday'=>$this->birthday,
            'onlyChild'=>$this->onlyChild,
            'address'=>$this->address,
            'phone'=>$this->phone,
            'socialMediaID'=>$this->socialMediaID,
            'numberOfGlasses'=>$this->numberOfGlasses,
            'leftHand'=>$this->leftHand,
            'religion'=>$this->religion,
            'specialDisease'=>$this->specialDisease,
        ];
    }
}
