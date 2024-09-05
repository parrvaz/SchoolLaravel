<?php

namespace App\Http\Resources\Grade;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Score\CourseCollection;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Student;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ExamCreateCollection extends ResourceCollection
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
                'classrooms' => new ClassroomCollection($item->classrooms),
                'courses' => new CourseCollection(Course::all()),
                'students' => new Student($item->students),
            ];
        })];
    }
}
