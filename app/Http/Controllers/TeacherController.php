<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentValidation;
use App\Http\Requests\Teacher\TeacherValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\Teacher;
use App\Models\User;
use Database\Factories\TeacherFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,TeacherValidation $validation)
    {
        return DB::transaction(function () use($request,$validation) {

        $teacher = Teacher::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
            'user_grade_id'=>$request->userGrade->id,
            'phone'=>$validation->phone,

        ]);
        //create user
        $user = User::create([
            "name"=> $teacher->firstName." ".$teacher->lastName,
            "phone"=>$teacher->phone,
            "password"=> bcrypt($teacher->nationalId),
        ]);

        //assign role
        $user->assignRole('teacher');
        $user->modelHasRole()->update(["idInRole"=>$teacher->id ]);

        return new TeacherResource($teacher);
        });

    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new TeacherCollection($request['userGrade']->teachers()->orderBy("lastName")->paginate($request->perPage??config("constant.bigPaginate")));
    }

    public function showSingle(Teacher $teacher)
    {
        return new TeacherResource($teacher);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherValidation $validation, Teacher $teacher)
    {
        $teacher->update([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
        ]);

        return new TeacherResource($teacher);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Teacher $teacher)
    {
        $teacher->delete();
        return $this->successMessage();
    }
}
