<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\ExamValidation;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamResource;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
   public function store(Request $request, ExamValidation $validation){

       return DB::transaction(function () use($request,$validation) {
           $exam = Exam::create([
               'user_grade_id' => $request['userGrade']->id,
               'classroom_id' => $validation->classroom_id,
               'date' => $validation->date,
               'course_id' => $validation->course_id,
               'expected' => $validation->expected,
               'totalScore' => $validation->totalScore,
           ]);

           $exam->contents()->attach($validation->contents);

           $exam->students()->createMany($validation->students);

           return $this->successMessage();
       });
   }

   public function show(Request $request){
       return new ExamCollection($request['userGrade']->exams()->paginate(config('constant.bigPaginate')));
   }

   public function showSingle(Exam $exam){
       return new ExamResource($exam);
   }

   public function delete(Exam $exam){
       


   }
}
