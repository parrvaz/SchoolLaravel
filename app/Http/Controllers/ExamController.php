<?php

namespace App\Http\Controllers;

use App\Exports\ExamExport;
use App\Http\Requests\Exam\ExamStoreValidation;
use App\Http\Resources\Exam\ExamCollection;
use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Exam\ScoreCollection;
use App\Models\Exam;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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



           if ($validation["students"]){

               $students =[];
               foreach ($validation->students as $std){
                   $students[]=[
                       "student_id"=>$std['student_id'],
                       "score"=>$std['score'],
                       'scaledScore' => $validation->totalScore ? ($std['score'] * 100) / $validation->totalScore : 0,
                   ];
               }
               $exam->students()->createMany($students);
           }


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
                    ->orderBy("updated_at","desc")
                    ->get();
               break;
           case config("constant.roles.assistant"):
           case config("constant.roles.manager"):
               $exams= $request->userGrade->exams()
                   ->orderBy("updated_at","desc")
                   ->get();
                break;
           case config("constant.roles.teacher"):
               $teacher = $user->teacher;
               $classCourse = $teacher->classCourses;
               $exams= $request->userGrade->exams()
                   ->whereIn("classroom_id",$classCourse->pluck("classroom_id"))
                   ->whereIn("course_id",$classCourse->pluck("course_id"))
                   ->orderBy("updated_at","desc")
                   ->get();
               break;
       }
       return new ExamCollection($exams);

   }

   public function showSingle($userGrade,Exam $exam){
       return new ExamResource($exam);
   }

   public function excel(Request $request,$userGrade,Exam $exam){

       $students = $this->calculateRank($exam);
       $name =self::gToJDash($exam->date). "-"."آزمون ". $exam->course->title.'-'."کلاس ".$exam->classroom->number.".xlsx" ;
       return Excel::download(new ExamExport($students), $name);
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
           if ($validation["students"]){

               $students =[];
               foreach ($validation->students as $std){
                   $students[]=[
                       "student_id"=>$std['student_id'],
                       "score"=>$std['score'],
                       'scaledScore' => ($std['score'] * 100) / $validation->totalScore,
                   ];
               }
               $exam->students()->createMany($students);
           }

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

    private function calculateRank($exam){
        $students = $exam->students()->orderBy("score","Desc")->get();
        $expected = $exam->expected;
        $total = $exam->totalScore;

        foreach ($students as $std){
            $score = $std->score;

                if  ($score == $total)
                    $std->rank = "😎";
                elseif ( $score >$total-(($total-$expected)/2))
                    $std->rank = "👌🏻";
                elseif ( $score >$expected)
                    $std->rank = "👍🏻";
                elseif ( $score >$expected/2)
                    $std->rank = "😐";
                elseif ( $score >$expected/4)
                    $std->rank = "🫢";
                else
                    $std->rank = "🤬";

        }

        return $students;
    }
}
