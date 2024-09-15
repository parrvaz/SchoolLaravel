<?php

namespace App\Http\Resources\Grade;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomWithStudentsCollection;
use App\Http\Resources\Course\CourseCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Course;
use App\Models\MenuItem;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GradeMItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'grades'=> $this->grades ?  new UserGradeCollection($this->grades) : null,
            'items'=>   new MenuItemCollection(MenuItem::where("parent_id",null)->get())

        ];
    }
}
