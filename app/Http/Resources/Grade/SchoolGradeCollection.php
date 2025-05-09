<?php

namespace App\Http\Resources\Grade;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SchoolGradeCollection extends ResourceCollection
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
                'id'=> $item->id,
                'fullName'=>  'پایه '. $item->grade_id .' '. $item->school->title,
                'schoolName'=>  $item->school->title,
                'purchasedStudents' => $item->purchasedStudents,
                'remainingStudents' => $item->purchasedStudents - $item->students->count(),
                'logo'=>   $item->school->logo ? url('storage/' . $item->school->logo) : null,
                'grade_id'=> $item->grade_id,
                'expiration'=> $item->deadline,
                'isActive'=> $item->isActive,
                'code'=> $item->code,
            ];
        })->toArray();
    }
}
