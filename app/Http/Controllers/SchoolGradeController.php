<?php

namespace App\Http\Controllers;

use App\Http\Requests\Grades\SchoolGradesValidation;
use App\Http\Resources\Grade\SchoolGradeCollection;
use App\Http\Resources\Grade\SchoolGradeResource;
use App\Models\SchoolGrade;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use mysql_xdevapi\Collection;

class SchoolGradeController extends Controller
{

    public function store(SchoolGradesValidation $validation){
        $grade = SchoolGrade::create([
            'school_id'=>auth()->user()->school->id,
            'grade_id'=>$validation->grade_id,
            'purchasedStudents'=>$validation->purchasedStudents ?? 0,
            'code'=> Str::random(30),
        ]);
        return new SchoolGradeResource($grade);
    }

    public function update(SchoolGrade $schoolGrade, SchoolGradesValidation $validation){
        $schoolGrade->update([
            'grade_id'=>$validation->grade_id ?? $schoolGrade->grade_id,
            'purchasedStudents'=>$validation->purchasedStudents ?? $schoolGrade->purchasedStudents,
        ]);

        return new SchoolGradeResource($schoolGrade);
    }

    public function updateCode(Request $request, SchoolGradesValidation $validation){

        $request->schoolGrade->update([
            'grade_id'=>$validation->grade_id ?? $request->schoolGrade->grade_id,
            'purchasedStudents'=>$validation->purchasedStudents ?? $request->schoolGrade->purchasedStudents,
        ]);

        return new SchoolGradeResource($request->schoolGrade);
    }

    public function show(){
        return new SchoolGradeCollection($this->getGrades());
    }

    public function delete(SchoolGrade $schoolGrade){
        $schoolGrade->delete();
        return $this->successMessage();
    }

    public function deleteCode( Request $request){
        $request->schoolGrade->delete();
        return $this->successMessage();
    }

    public function getGrades(){
        $grades = [];
        $user =  auth()->user();
        $role =$user->role;
        $school=$user->school;
        switch ($role){
            case config("constant.roles.assistant"):
                $teacher = $user->teacher;
                $grades = SchoolGrade::where('school_id',$teacher->school_id)->get();
                break;
            case config("constant.roles.teacher"):
                $teacher = $user->teacher;
                $schoolGradeIds =  $teacher->classrooms->pluck("school_grade_id")->unique()->values();
                $grades = SchoolGrade::whereIn("id",$schoolGradeIds)->get();
                break;
            case config("constant.roles.manager"):
                $grades = SchoolGrade::where('school_id',$school->id)->get();
                break;
            case config("constant.roles.student"):
                $classroom = $user->student->classroom;
                $grades = SchoolGrade::where('id',$classroom->school_grade_id)->get();
                break;
            case config("constant.roles.parent"):
                $students = $user->students;
                $schoolGrades=collect();
                foreach ($students as $student){
                    $schoolGrades->add($student->classroom->schoolGrade);
                }
                $grades = $schoolGrades;
                break;

        }

        return $grades;
    }
}
