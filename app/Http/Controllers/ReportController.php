<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Reports\ExamCountCollection;
use App\Http\Resources\Reports\ListItemsResource;
use App\Models\Absent;
use App\Models\ClassScore;
use App\Models\Exam;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function absents(Request $request,FilterValidation $validation){
        $absents = Absent::query();


    }

    public function card(Request $request){
        $userGrade = $request->userGrade;

        $exams =  Exam::query()->where("exams.user_grade_id",$userGrade->id)
            ->join("student_exam","exams.id","student_exam.exam_id")
            ->rightJoin("courses","courses.id","exams.course_id")
            ->where("courses.grade_id",$userGrade->grade_id);

        $exams = $exams->groupBy("course_id");
        $exams = $exams->select(
            DB::raw("course_id"),
            DB::raw("ROUND(AVG(student_exam.score),1) as score"),
//            DB::raw("ROUND(AVG(student_exam.score),1) as averageScore"),
//            DB::raw("ROUND(AVG(exams.totalScore),1) as totalScore"),
//            DB::raw("ROUND(AVG(exams.expected),1) as expected"),
        );
        $exams = $exams->get();
        return $exams;
    }
    public function progress(Request $request,FilterValidation $validation){
        $userGrade = $request->userGrade;
        $exams= Exam::query()->where("user_grade_id",$userGrade->id)
            ->join("student_exam","exams.id","student_exam.exam_id");

        $exams =  $exams->orderBy("date")
            ->groupBy("exams.date")
            ->select(
                "exams.date",
                DB::raw("MIN(exams.id) as id"),
                DB::raw("ROUND(AVG(student_exam.score),1) as score"),
                DB::raw("ROUND(AVG(student_exam.score),1) as averageScore"),
                DB::raw("ROUND(AVG(exams.totalScore),1) as totalScore"),
                DB::raw("ROUND(AVG(exams.expected),1) as expected"),
                );

        $exams= $exams->get();


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
}
