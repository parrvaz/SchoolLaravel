<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\ClassroomValidation;
use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomResource;
use App\Models\Classroom;
use App\Models\UserGrade;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request,ClassroomValidation $validation)
    {
        $classroom = Classroom::create([
            'title'=>$validation->title,
            'number'=>$validation->number,
            'floor'=>$validation->floor,
            'user_grade_id'=>$request->userGrade->id,
            'field_id'=>$validation->field_id,
        ]);

        return new ClassroomResource($classroom);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return new ClassroomCollection($request['userGrade']->classrooms()->paginate(config("constant.paginate")));
    }

    public function showSingle(Classroom $classroom)
    {
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomValidation $validation, Classroom $classroom)
    {
        $classroom->update([
            'title'=>$validation->title,
            'number'=>$validation->number,
            'floor'=>$validation->floor,
            'field_id'=>$validation->field_id,
        ]);

        return new ClassroomResource($classroom);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Classroom $classroom)
    {
        $classroom->delete();
        return $this->successMessage();
    }
}
