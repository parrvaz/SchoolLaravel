<?php

namespace App\Http\Resources\Auth;

use App\Http\Controllers\SchoolGradeController;
use App\Http\Resources\Grade\SchoolGradeCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\SchoolGrade;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_admin' => auth()->user()->role == config("constant.roles.admin"),
            'role'=>$this->getRoleNames()->first(),
            'role_id'=>$this->modelHasRole->idInRole ?? null,
            'hasChanged'=>(bool) $this->hasChanged ?? false,
            'teacher'=> new TeacherResource($this->teacher),
            'grades'=> new SchoolGradeCollection((new SchoolGradeController())->getGrades()),
        ];
    }
}
