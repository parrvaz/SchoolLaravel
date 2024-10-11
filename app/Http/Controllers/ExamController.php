<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\ExamStoreValidation;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Exam\ScoreCollection;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
   public function store(Request $request, ExamStoreValidation $validation){

       return DB::transaction(function () use($request,$validation) {
           $exam = Exam::create([
               'user_grade_id' => $request->userGrade->id,
               'classroom_id' => $validation->classroom_id,
               'date' =>self::jToG($validation->date),
               'course_id' => $validation->course_id,
               'expected' => $validation->expected ?? 0,
               'totalScore' => $validation->totalScore ?? 0,
               'status' => $validation->isFinal ?? false,
               'type' => $validation->type ?? 1,
               'isGeneral' => $validation->isGeneral ?? false,
           ]);

           $exam->contents()->attach($validation->contents);



           if ($validation["students"])
           $exam->students()->createMany($validation->students);


           return $this->successMessage();
       });
   }

   public function show(Request $request){
       $user =  auth()->user();
       $role =$user->role;
       $exams = [];
       switch ($role){
           case config("constant.roles.student"):
           case config("constant.roles.parent"):
                $exams= $request->userGrade->exams()
                    ->where("classroom_id",$user->student->classroom_id)
                    ->whereIn("type",[1,3])
                    ->get();
               break;
           case config("constant.roles.assistant"):
           case config("constant.roles.manager"):
               $exams= $request->userGrade->exams()->get();
                break;
           case config("constant.roles.teacher"):
               $teacher = $user->teacher;
               $classCourse = $teacher->classCourses;
               $exams= $request->userGrade->exams()
                   ->whereIn("classroom_id",$classCourse->pluck("classroom_id"))
                   ->whereIn("course_id",$classCourse->pluck("course_id"))
                   ->get();
               break;
       }
       return new ExamCollection($exams);

   }

   public function showSingle($userGrade,Exam $exam){
       return new ExamResource($exam);
   }

   public function scores(Request $request){
       $user =  auth()->user();
       $student = $user->student;
      return new ScoreCollection(StudentExam::where("student_id",$student->id)->get());
   }

   public function update(ExamStoreValidation $validation, $userGrade, Exam $exam){
       return DB::transaction(function () use($exam,$validation) {

           //delete old items
           $this->deleteExamContents($exam);
           $this->deleteExamStudents($exam);

           //update exam main data
           $exam->update([
               'classroom_id' => $validation->classroom_id,
               'date' =>self::jToG($validation->date),
               'course_id' => $validation->course_id,
               'expected' => $validation->expected ?? 0,
               'totalScore' => $validation->totalScore ?? 0,
               'status' => $validation->isFinal ?? false,
               'type' => $validation->type ?? 1,
               'isGeneral' => $validation->isGeneral ?? false,
           ]);

           //create content items
           $exam->contents()->attach($validation->contents);

           //create student items
           $exam->students()->createMany($validation->students);

           return $this->successMessage();
       });
   }

   public function delete($userGrade,Exam $exam){
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
