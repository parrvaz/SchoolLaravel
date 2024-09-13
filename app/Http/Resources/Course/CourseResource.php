<?php

namespace App\Http\Resources\Course;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomWithStudentsCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'name'=> $this->name,
            'grade_id'=> $this->grade_id,
            'factor'=> $this->factor,
            'type'=> $this->type ? "تخصصی" : "عمومی",
            'contentCount'=> $this->contents->groupBy("content")->count(),
            'contents'=> new ContentCollection($this->contents),
        ];
    }
}
