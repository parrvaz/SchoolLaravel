<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\ClassroomValidation;
use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Classroom\ClassroomResource;
use App\Http\Resources\Grade\ExamCreateCollection;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Models\Classroom;
use App\Models\SchoolGrade;
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
            'school_grade_id'=>$request->schoolGrade->id,
            'field_id'=>$validation->field_id,
        ]);

        return new ClassroomResource($classroom);
    }


    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $classrooms = [];
        switch (auth()->user()->role){
            case config("constant.roles.assistant"):
            case config("constant.roles.manager"):
                $classrooms =$request->schoolGrade->classrooms()->get();
                break;

            case config("constant.roles.teacher"):
                $teacher = auth()->user()->teacher;
               $classrooms = $teacher->classrooms;
                break;

        }
        return new ClassroomCollection($classrooms);
    }

    public function showSingle($schoolGrade,Classroom $classroom)
    {
        return new ClassroomResource($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClassroomValidation $validation,$schoolGrade, Classroom $classroom)
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
    public function delete($schoolGrade,Classroom $classroom)
    {
        if ($classroom->students->count() > 0)
            return $this->error("hasStudent");

        $classroom->delete();
        return $this->successMessage();
    }

    public function list(Request $request){
        return new ExamCreateResource($request->schoolGrade);
    }
}
