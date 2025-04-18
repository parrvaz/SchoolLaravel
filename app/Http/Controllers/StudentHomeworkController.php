<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\StudentHomeworkStoreValidation;
use App\Http\Requests\Exam\StudentHomeworkUpdateValidation;
use App\Http\Resources\Homework\StudentHomeworkCollection;
use App\Http\Resources\Homework\StudentHomeworkResource;
use App\Models\Homework;
use App\Models\Student;
use App\Models\StudentHomework;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentHomeworkController extends Controller
{

    public function store(Request $request,StudentHomeworkStoreValidation $validation){
        $stdId = auth()->user()->modelHasRole->idInRole;
        if ( StudentHomework::where("homework_id" ,$validation->homework_id)
            ->where("student_id",$stdId)->exists()
        )
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($request,$validation,$stdId) {
            $solution =  $this->saveSingleFile($request, "students/homework", "pdf");
            $homework  = StudentHomework::create([
                "student_id"=> $stdId ,
                'homework_id'=>$validation->homework_id,
                'solution'=>$solution,
                'note'=>$validation->note,
            ]);
            return $this->successMessage();

        });
    }




    public function update(Request $request,StudentHomeworkUpdateValidation $validation,$schoolGrade,StudentHomework $studentHomework){
        if ($studentHomework->score != null || $studentHomework->student_id != auth()->user()->modelHasRole->idInRole)
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($request,$validation,$studentHomework) {
            $this->deleteFile($studentHomework->solution);
            $solution =  $this->saveSingleFile($request, "students/homework", "pdf");
            $studentHomework->update([
                'solution'=>$solution,
                'note'=>$validation->note,
            ]);
            return $this->successMessage();
        });
    }

    public function delete($schoolGrade,StudentHomework $studentHomework){
        if ($studentHomework->score != null || $studentHomework->student_id != auth()->user()->modelHasRole->idInRole)
            return $this->error("permissionForUser",403);

        return DB::transaction(function () use($studentHomework) {
            $this->deleteFile($studentHomework->solution);
            $studentHomework->delete();
            return $this->successMessage();
        });
    }


    public function show(){
            $student = auth()->user()->student;
            $homework = $student->classroom->homework()->get();
            return new StudentHomeworkCollection($homework);
    }

    public function showSingle($schoolGrade,Homework $homework){

        if (!in_array(auth()->user()->student->classroom_id, array_intersect($homework->classrooms->pluck("id")->toArray())))
            return $this->error("permissionForUser",403);
        return new StudentHomeworkResource($homework);
    }

}
