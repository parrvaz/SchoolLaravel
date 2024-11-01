<?php

namespace App\Http\Resources\Reports\Card;

use App\Http\Resources\Course\ContentCollection;
use App\Models\Course;
use App\Models\Student;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CardSeparateCollection extends ResourceCollection
{
    use ServiceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($item,$key){
            $std = Student::find($key);
            return[
                'student_id'=>$std->id,
                'name'=>$std->name,
                'lastName'=>$std->lastName,
                'classroom_id'=>$std->classroom_id,
                'classroom'=>$std->classroom->title,
                'field_id'=>$std->classroom->field_id,
                'field'=>$std->classroom->field->title,
                'average'=>$item["average"],

                'scores' =>new CardCourseCollection($item['scores']) ?? null,


            ];
        })->toArray();
    }
}
