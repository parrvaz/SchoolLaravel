<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Reports\ExamCountCollection;
use App\Http\Resources\Reports\ListItemsResource;
use App\Models\ClassScore;
use App\Models\Exam;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function listItems(Request $request){
        return new ListItemsResource($request["userGrade"]);
    }
    public function allExamCount(Request $request,FilterValidation $validation){


//        \App\Models\ClassScore::factory()->count(6)->create();
        $userGrade = $request['userGrade'];
        $exams= $this->examCount($userGrade,$validation);
        $classScore= $this->classScoreCount($userGrade,$validation);
        $result = collect([
            'userGrade'=>$userGrade,
            'exam' => $exams,
            'classScore' => $classScore,
            "tickValues"=> $exams->pluck("id")->merge($classScore->pluck("id"))->unique() ->values(),
            "tickFormat"=>  $exams->pluck("title")->merge($classScore->pluck("title"))->unique() ->values(),

        ]);
        return new AllCountResource($result);
    }

    public function examProgress(Request $request,FilterValidation $validation){
        $userGrade = $request['userGrade'];
        $exams=  $this->examJoined($userGrade,$validation,true);

        $exams =  $exams->orderBy("date")
            ->groupBy("exams.date")
            ->select(
                "exams.date",
                DB::raw("MIN(exams.id) as id"),
                DB::raw("ROUND(AVG(student_exam.score),1) as score"),
                DB::raw("ROUND(AVG(exams.totalScore),1) as totalScore"),
                DB::raw("ROUND(AVG(exams.expected),1) as expected"),
                );

        $exams= $exams->get();

        $exams = $exams->map(function($exam) {
            $exam->score = (float) $exam->score;
            $exam->totalScore = (float) $exam->totalScore;
            $exam->expected = (float) $exam->expected;
            return $exam;
        });

        $result = collect([
            'userGrade'=>$userGrade,
            'exam' => $exams,
            "tickValues"=> $exams->pluck("id"),
            "tickFormat"=> $exams->pluck("date")->map(function ($item){
                return ServiceTrait::gToJ( $item);
              })->toArray(),
        ]);

        return new AllCountResource($result);
    }

    public function classScoreProgress(Request $request,FilterValidation $validation){
        $userGrade = $request['userGrade'];
        $classScore=  $this->classScoreJion($userGrade,$validation,true);
        $classScore =  $classScore->orderBy("date")
            ->groupBy("class_scores.date")
            ->select(
                "class_scores.date",
                DB::raw("MIN(class_scores.id) as id"),
                DB::raw("ROUND(AVG(student_class_score.score),1) as score"),
                DB::raw("ROUND(AVG(class_scores.totalScore),1) as totalScore"),
                DB::raw("ROUND(AVG(class_scores.expected),1) as expected"),
            );

        $classScore= $classScore->get();

        $classScore = $classScore->map(function($exam) {
            $exam->score = (float) $exam->score;
            $exam->totalScore = (float) $exam->totalScore;
            $exam->expected = (float) $exam->expected;
            return $exam;
        });

        $result = collect([
            'userGrade'=>$userGrade,
            'exam' => $classScore,
            "tickValues"=> $classScore->pluck("id"),
            "tickFormat"=> $classScore->pluck("date")->map(function ($item){
                return ServiceTrait::gToJ( $item);
            })->toArray(),
        ]);

        return new AllCountResource($result);
    }


    /** private */
    private function examCount($userGrade,$validation){
        $exams=  $this->examJoined($userGrade,$validation);
        $exams =  $exams
            ->groupBy("courses.id", "courses.title")
            ->select(
                "courses.id",
                "courses.title",
                DB::raw("COUNT(exams.id) as count",
                ));

        $exams= $exams->get();

        return $exams;
    }

    private function classScoreCount($userGrade,$validation){
        $class_scores =  $this->classScoreJion($userGrade,$validation)
            ->groupBy("courses.id", "courses.title")
            ->select(
                "courses.id",
                "courses.title",
                DB::raw("COUNT(class_scores.id) as count",
                ));
        $class_scores= $class_scores->get();

        return $class_scores;
    }

    private function examJoined($userGrade,$validation,$joinStd=false){
        $exams =  Exam::query()
            ->where('exams.user_grade_id',$userGrade->id)
            ->where('status',true)
            ->join('courses', 'exams.course_id', '=', 'courses.id')
            ->join('classrooms', 'exams.classroom_id', '=', 'classrooms.id');

        if($joinStd){
            $exams = $exams->join("student_exam","exams.id","student_exam.exam_id");
            $exams = $this->globalFilterWhereIn($exams,$validation["student"],"student_exam.student_id");
        }else{
            $exams= $this->globalFilterRelationWhereIn($exams,"student_exam.student_id",$validation["student"],"students");
        }

        $exams= $this->globalFilterWhereIn($exams,$validation["course"],"course_id");
        $exams= $this->globalFilterWhereIn($exams,$validation["classroom"],"classroom_id");

        return $exams;
    }

    private function classScoreJion($userGrade,$validation,$joinStd=false){
        $class_scores =  ClassScore::query()
            ->where('class_scores.user_grade_id',$userGrade->id)
            ->where('status',true)
            ->join('courses', 'class_scores.course_id', '=', 'courses.id')
            ->join('classrooms', 'class_scores.classroom_id', '=', 'classrooms.id')
          ;
        if ($joinStd){
            $class_scores = $class_scores->join("student_class_score","class_scores.id","student_class_score.class_score_id");
            $class_scores = $this->globalFilterWhereIn($class_scores,$validation["student"],"student_class_score.student_id");

        }else{
            $class_scores= $this->globalFilterRelationWhereIn($class_scores,"student_class_score.student_id",$validation["student"],"students");
        }
        $class_scores= $this->globalFilterWhereIn($class_scores,$validation["course"],"course_id");
        $class_scores= $this->globalFilterWhereIn($class_scores,$validation["classroom"],"classroom_id");

        return $class_scores;
    }

}
