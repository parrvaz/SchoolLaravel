<?php

namespace App\Http\Controllers;

use App\Exports\AbsentsExport;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Reports\AllCountResource;
use App\Models\Absent;
use App\Models\Exam;
use App\Models\StudentExam;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function absents(Request $request,FilterValidation $validation){

        $classroomsIds = $validation->classrooms ?? $request->userGrade->classrooms()->pluck("id");

        $allAbs = Absent::query();
        $allAbs = self::filterByDate($allAbs,$validation->startDate,$validation->endDate);
        $allAbs = self::globalFilterWhereIn($allAbs,"classroom_id",$classroomsIds);
        $allAbs = $allAbs->groupBy("classroom_id")
            ->select("classroom_id",
                DB::raw('count(*) as total'))
            ->get();

        $classrooms = [];
        foreach ($allAbs as $abs){
            $classrooms[$abs->classroom_id]=$abs->total;
        }

        $absents = Absent::query()
            ->leftJoin("absent_student","absent_student.absent_id","absents.id")
            ->join("students","absent_student.student_id","students.id");
        $absents = self::filterByDate($absents,$validation->startDate,$validation->endDate);
        $absents = self::globalFilterWhereIn($absents,"absents.classroom_id",$classroomsIds);
        $absents = $absents
            ->groupBy("student_id","absents.classroom_id","students.firstName","students.lastName")
            ->select("student_id","absents.classroom_id","students.firstName","students.lastName",
                DB::raw('count(*) as number'))
            ->orderBy(DB::raw('count(*)'),"DESC")
            ->get();

        //absents map
        foreach ($absents as $absent){
            $absent->total = $classrooms[$absent->classroom_id];
            $absent->percent=  ($absent->number / $absent->total) * 100 ?? 0;
            $absent->classroomTitle =  $absent->classroom->title;

            if  ($absent->percent < 5)
                $absent->rank = "😓";
            elseif ( $absent->percent < 10)
                $absent->rank = "😢";
            elseif ($absent->percent < 25)
                $absent->rank = "😳";
            elseif ( $absent->percent < 40)
                $absent->rank = "🤯";
            else
                $absent->rank = "😡";

        }

        $name = "لیست غیبت ها" ;
        if ($validation->startDate)
            $name= \Carbon\Carbon::createFromFormat('Y/m/d',$validation->startDate)->format('Y-m-d')."-" . $name;
        return Excel::download(new AbsentsExport($absents), $name.".xlsx");


    }

    public function card(Request $request){
        $userGrade = $request->userGrade;

//        $exams =  Exam::query()->where("exams.user_grade_id",$userGrade->id)
//            ->join("student_exam","exams.id","student_exam.exam_id")
//            ->join("course_fields","course_fields.course_id","exams.course_id")
//            ->where("course_fields.field_id","student_exam.classroom")
////            ->whereHas('student.classroom', function($query) use($request) {
////                return $query->where('field_id', "course_fields.field_id");
////            })
////            ->rightJoin("courses","courses.id","exams.course_id")
////            ->where("courses.grade_id",$userGrade->grade_id)
//        ;


        $studentExam = StudentExam::query()
            ->join("exams","exams.id","student_exam.exam_id")
            ->join("course_fields","course_fields.course_id","exams.course_id")
            ->join("classrooms","classrooms.id","exams.classroom_id")
//            ->where("classrooms.field_id","course_fields.field_id")
//            ->where("student_exam.student_id",1)
//            ->whereHas('exam.classroom', function($query) use($request) {
//                return $query;
////                    ->where('id', "exam.classroom_id");
//            })
//            ->join("students","students.id","student_exam.student_id")
        ;


        $studentExam = $studentExam->groupBy("exams.course_id","factor");
        $studentExam = $studentExam->select(
            DB::raw("exams.course_id"),
            DB::raw("ROUND(AVG(student_exam.score),1) as score"),
//            DB::raw("ROUND(AVG(student_exam.score),1) as averageScore"),
//            DB::raw("ROUND(AVG(exams.totalScore),1) as totalScore"),
//            DB::raw("ROUND(AVG(exams.expected),1) as expected"),
        );
        $studentExam = $studentExam->get();
        return $studentExam;
    }
    public function progress(Request $request){
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
