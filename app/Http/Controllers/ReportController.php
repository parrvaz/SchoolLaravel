<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Reports\AllCountCollection;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Reports\ExamCountCollection;
use App\Models\ClassScore;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function allExamCount(Request $request,FilterValidation $validation){

//        \App\Models\ClassScore::factory()->count(6)->create();
        $userGrade = $request['userGrade'];

        $exams= $this->examCount($userGrade,$validation);
        $classScore= $this->classScoreCount($userGrade,$validation);


        $result = collect([
            'exam' => $exams,
            'classScore' => $classScore,
            "tickValues"=> $exams->pluck("id")->merge($classScore->pluck("id")),
            "tickFormat"=> $exams->pluck("title")->merge($classScore->pluck("title")),

        ]);

        return new AllCountResource($result);
    }

    private function examCount($userGrade,$validation){
        $exams =  Exam::query()
            ->where('exams.user_grade_id',$userGrade->id)
            ->where('status',true)
            ->join('courses', 'exams.course_id', '=', 'courses.id')
            ->join('classrooms', 'exams.classroom_id', '=', 'classrooms.id')
            ->groupBy("courses.id", "courses.title")
            ->select(
                "courses.id",
                "courses.title",
                DB::raw("COUNT(exams.id) as count",
                ));
        $exams= $this->globalFilter($exams,$validation["course_id"],"course_id");
        $exams= $this->globalFilter($exams,$validation["classroom_id"],"classroom_id");
        $exams= $this->globalFilterRelation($exams,"student_exam.student_id",$validation["student_id"],"students");

        $exams= $exams->get();

        return $exams;
    }

    private function classScoreCount($userGrade,$validation){
        $class_scores =  ClassScore::query()
            ->where('class_scores.user_grade_id',$userGrade->id)
            ->where('status',true)
            ->join('courses', 'class_scores.course_id', '=', 'courses.id')
            ->join('classrooms', 'class_scores.classroom_id', '=', 'classrooms.id')
            ->groupBy("courses.id", "courses.title")
            ->select(
                "courses.id",
                "courses.title",
                DB::raw("COUNT(class_scores.id) as count",
                ));
        $class_scores= $this->globalFilter($class_scores,$validation["course_id"],"course_id");
        $class_scores= $this->globalFilter($class_scores,$validation["classroom_id"],"classroom_id");
        $class_scores= $this->globalFilterRelation($class_scores,"student_class_score.student_id",$validation["student_id"],"students");

        $class_scores= $class_scores->get();

        return $class_scores;
    }

}
