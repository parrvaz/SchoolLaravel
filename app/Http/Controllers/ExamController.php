<?php

namespace App\Http\Controllers;

use App\Http\Requests\Score\ScoreValidation;
use App\Http\Resources\Score\ScoreCollection;
use App\Http\Resources\Score\ScoreResource;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
   public function store(Request $request, ScoreValidation $validation){

       return DB::transaction(function () use($request,$validation) {
           $exam = Exam::create([
               'user_grade_id' => $request['userGrade']->id,
               'classroom_id' => $validation->classroom_id,
               'date' => $validation->date,
               'course_id' => $validation->course_id,
               'expected' => $validation->expected,
               'totalScore' => $validation->totalScore,
               'status' => $validation->status ?? false,
           ]);

           $exam->contents()->attach($validation->contents);



           if ($validation["students"])
           $exam->students()->createMany($validation->students);


           return $this->successMessage();
       });
   }

   public function show(Request $request){
       return new ScoreCollection($request['userGrade']->exams()->paginate(config('constant.bigPaginate')));
   }

   public function showSingle(Exam $exam){
       return new ScoreResource($exam);
   }

   public function update(ScoreValidation $validation, Exam $exam){
       return DB::transaction(function () use($exam,$validation) {

           //delete old items
           $this->deleteExamContents($exam);
           $this->deleteExamStudents($exam);

           //update exam main data
           $exam->update([
               'classroom_id' => $validation->classroom_id,
               'date' => $validation->date,
               'course_id' => $validation->course_id,
               'expected' => $validation->expected,
               'totalScore' => $validation->totalScore,
           ]);

           //create content items
           $exam->contents()->attach($validation->contents);

           //create student items
           $exam->students()->createMany($validation->students);

           return new ScoreResource($exam);
       });
   }

   public function delete(Exam $exam){
       return DB::transaction(function () use($exam) {

           $this->deleteExamContents($exam);
           $this->deleteExamStudents($exam);
           $exam->delete();
           return $this->successMessage();
       });
   }

    private function deleteExamContents(Exam $exam)
    {
        $exam->contents()->detach();
    }

    private function deleteExamStudents(Exam $exam)
    {
        $exam->students()->delete();
    }
}
