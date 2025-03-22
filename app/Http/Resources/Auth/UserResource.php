<?php

namespace App\Http\Resources\Auth;

use App\Http\Controllers\SchoolGradeController;
use App\Http\Resources\Grade\SchoolGradeCollection;
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
            'is_admin' => $this->id == 7,
            'role'=>$this->getRoleNames()->first(),
            'role_id'=>$this->modelHasRole->idInRole ?? null,
            'hasChanged'=>(bool) $this->hasChanged ?? false,
            'grades'=> new SchoolGradeCollection((new SchoolGradeController())->getGrades()),
        ];
    }
}
