<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\UserGradesValidation;
use App\Http\Resources\Grade\UserGradeCollection;
use App\Http\Resources\Grade\UserGradeResource;
use App\Models\SchoolGrade;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SchoolGradeController extends Controller
{

    public function store(UserGradesValidation $validation){
        $grade = SchoolGrade::create([
            'school_id'=>auth()->user()->school->id,
            'grade_id'=>$validation->grade_id,
            'title'=>$validation->title,
            'code'=> Str::random(30),
        ]);
        return new UserGradeResource($grade);
    }

    public function update(SchoolGrade $userGrade, UserGradesValidation $validation){
        $userGrade->update([
            'grade_id'=>$validation->grade_id,
            'title'=>$validation->title,
        ]);

        return new UserGradeResource($userGrade);
    }

    public function updateCode( Request $request,UserGradesValidation $validation){

        $request->userGrade->update([
            'grade_id'=>$validation->grade_id ?? $request->userGrade->grade_id,
            'title'=>$validation->title ?? $request->userGrade->title,
        ]);

        return new UserGradeResource($request->userGrade);
    }

    public function show(){


        return new UserGradeCollection($this->getGrades());
    }

    public function delete(SchoolGrade $schoolGrade){
        $schoolGrade->delete();
        return $this->successMessage();
    }

    public function deleteCode( Request $request){
        $request->userGrade->delete();
        return $this->successMessage();
    }

    public function getGrades(){
        $grades = [];
        $user =  auth()->user();
        $role =$user->role;
        switch ($role){
            case config("constant.roles.assistant"):
            case config("constant.roles.teacher"):
                $teacher = $user->teacher;
                $grades = SchoolGrade::where('user_id',$teacher->user_id)->get();
                break;
            case config("constant.roles.manager"):
                $grades = SchoolGrade::where('user_id',auth()->user()->id)->get();
                break;
            case config("constant.roles.student"):
            case config("constant.roles.parent"):
                $classroom = $user->student->classroom;
                $grades = SchoolGrade::where('id',$classroom->user_grade_id)->get();
                break;
        }

        return $grades;
    }
}
