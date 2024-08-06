<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StudentValidation;
use App\Http\Requests\Teacher\TeacherValidation;
use App\Http\Resources\Student\StudentCollection;
use App\Http\Resources\Student\StudentResource;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Teacher\TeacherResource;
use App\Models\Teacher;
use Database\Factories\TeacherFactory;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,TeacherValidation $validation)
    {
        $teacher = Teacher::create([
            'firstName'=>$validation->firstName,
            'lastName'=>$validation->lastName,
            'nationalId'=>$validation->nationalId,
            'degree'=>$validation->degree,
            'personalId'=>$validation->personalId,
            'user_grade_id'=>$request->userGrade->id,
        ]);

        return new TeacherResource($teacher);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new TeacherCollection($request['userGrade']->teachers()->paginate(config("constant.bigPaginate")));
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
