<?php

namespace App\Http\Resources\Grade;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomWithStudentsCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamCreateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'classrooms' => new ClassroomWithStudentsCollection($this->classrooms),
            'courses' => new CourseCollection(Course::all()),
            'students' => new StudentCollection($this->students),
        ];
    }
}
