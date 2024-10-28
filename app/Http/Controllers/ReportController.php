<?php

namespace App\Http\Controllers;

use App\Exports\AbsentsExport;
use App\Exports\CardExport;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Reports\AbsentsReportCollection;
use App\Http\Resources\Reports\AllCountResource;
use App\Http\Resources\Reports\Card\CardResource;
use App\Http\Resources\Reports\Card\CardSeparateCollection;
use App\Http\Resources\Reports\Progress\ProgressCollection;
use App\Models\Absent;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentExam;
use App\Traits\ServiceTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{

    public function absents(Request $request,FilterValidation $validation){
        $absents = $this->absentMtd($request,$validation);
        return new AbsentsReportCollection($absents);
    }
    public function absentsExcel(Request $request,FilterValidation $validation){


        $absents = $this->absentMtd($request,$validation);
        $name = "لیست غیبت ها" ;
        if ($validation->startDate)
            $name= \Carbon\Carbon::createFromFormat('Y/m/d',$validation->startDate)->format('Y-m-d')."-" . $name;
        return Excel::download(new AbsentsExport($absents), $name.".xlsx");


    }


    public function card(Request $request,FilterValidation $validation){

        $result = $this->cardMtd($request,$validation);
        if (!$validation->isSeparate)
            return new CardResource($result);
        else
            return new CardSeparateCollection($result);

    }

    public function cardExcel(Request $request,FilterValidation $validation){
        $result = $this->cardMtd($request,$validation);
        return Excel::download(new CardExport($result['studentExam']), "کارنامه".".xlsx");



    }
    public function progress(Request $request,FilterValidation $validation){
        $exams= Exam::query()
            ->where("exams.user_grade_id", $request->userGrade->id)
            ->where("exams.status",1)
            ->join("student_exam","exams.id","student_exam.exam_id")
            ->join("classrooms","classrooms.id","exams.classroom_id")
            ->leftJoin('course_fields', function ($join) {
                $join->on('course_fields.course_id', '=', 'exams.course_id')
                    ->where(function ($query) {
                        $query->whereColumn('course_fields.field_id', 'classrooms.field_id')
                            ->orWhereNull('course_fields.field_id');
                    });
            })
            ;
        $exams = $this->globalFilterWhereIn($exams,"exams.type",$validation->types);
        $exams = $this->globalFilterWhereIn($exams,"exams.classroom_id",$validation->classrooms);
        $exams = $this->globalFilterWhereIn($exams,"exams.course_id",$validation->courses);
        $exams = $this->globalFilterWhereIn($exams,"student_exam.student_id",$validation->students);
        $exams = $this->filterByDate($exams,$validation->startDate,$validation->endDate);

        $exams =  $exams->orderBy("date")
            ->groupBy("exams.date")
            ->select(
                "exams.date",
                DB::raw("MIN(exams.id) as id"),
                DB::raw("ROUND( SUM((student_exam.scaledScore /5 ) * course_fields.factor) / SUM(course_fields.factor)  ,2) as score"),
                DB::raw("ROUND(AVG( ROUND((exams.expected/exams.totalScore)*20,2 ) ),2) as expected"),
                );

        $exams= $exams->get();

//        return $exams;
        $classExam=collect();
        if($validation->students != null){
            $classroomsIds = Student::whereIn("id",$validation->students)->pluck("classroom_id");
            $classExam =  Exam::query()
                ->where("exams.user_grade_id", $request->userGrade->id)
                ->where("exams.status",1)
                ->join("student_exam","exams.id","student_exam.exam_id")
                ->join("classrooms","classrooms.id","exams.classroom_id")
                ->leftJoin('course_fields', function ($join) {
                    $join->on('course_fields.course_id', '=', 'exams.course_id')
                        ->where(function ($query) {
                            $query->whereColumn('course_fields.field_id', 'classrooms.field_id')
                                ->orWhereNull('course_fields.field_id');
                        });
                })
            ;
            $classExam = $this->globalFilterWhereIn($classExam,"exams.type",$validation->types);
            $classExam = $this->globalFilterWhereIn($classExam,"exams.classroom_id",$classroomsIds);
            $classExam = $this->globalFilterWhereIn($classExam,"exams.course_id",$validation->courses);
            $classExam = $this->filterByDate($classExam,$validation->startDate,$validation->endDate);


            $classExam =  $classExam->orderBy("date")
                ->groupBy("exams.date")
                ->select(
                    "exams.date",
                    DB::raw("MIN(exams.id) as id"),
                    DB::raw("ROUND( SUM((student_exam.scaledScore /5 ) * course_fields.factor) / SUM(course_fields.factor)  ,2) as score"),
                );

            $classExam= $classExam->get();
        }



        return new ProgressCollection($exams,$classExam);
    }


    private function absentMtd($request,$validation){
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
            $absent->percent= (int)(($absent->number / $absent->total) * 100 )?? 0;
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


        $absents = $absents->sortByDesc("percent")->values();

        return $absents;
    }

    private function cardMtd($request,$validation){
        $studentExam = StudentExam::query()
            ->join("exams","exams.id","student_exam.exam_id")
            ->join("classrooms","classrooms.id","exams.classroom_id")
            ->leftJoin('course_fields', function ($join) {
                $join->on('course_fields.course_id', '=', 'exams.course_id')
                    ->where(function ($query) {
                        $query->whereColumn('course_fields.field_id', 'classrooms.field_id')
                            ->orWhereNull('course_fields.field_id');
                    });
            })

            ->where("exams.status",1)
            ->where("exams.user_grade_id",$request->userGrade->id)
        ;


        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.type",$validation->types);
        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.classroom_id",$validation->classrooms);
        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.course_id",$validation->courses);
        $studentExam = $this->globalFilterWhereIn($studentExam,"exams.id",$validation->exams);
        $studentExam = $this->globalFilterWhereIn($studentExam,"student_exam.student_id",$validation->students);
        $studentExam = $this->filterByDate($studentExam,$validation->startDate,$validation->endDate);

        $result = [];

        if (!$validation->isSeparate){
            $studentExam = $studentExam->groupBy(
                "exams.course_id",
                "course_fields.factor",
            );

            $studentExam = $studentExam->select(
                "exams.course_id",
                "course_fields.factor",
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) as score"),
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) * factor as wightedScore"),
            );
            $studentExam = $studentExam->get();

            $factors = $studentExam->sum("factor");
            $wightedScores = $studentExam->sum("wightedScore");
            $average = round( $wightedScores / $factors,2);
            $result['average'] = $average;
            $result['studentExam'] = $studentExam;
        }else{
            $studentExam = $studentExam->groupBy(
                "exams.course_id",
                "course_fields.factor",
                "student_exam.student_id"
            );

            $studentExam = $studentExam->select(
                "exams.course_id",
                "course_fields.factor",
                "student_exam.student_id",

                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) as score"),
                DB::raw("ROUND(AVG(student_exam.scaledScore) / 5,2) * factor as wightedScore"),
            );

            $studentExam = $studentExam->get();

            $students =  $studentExam->groupBy("student_id");
            foreach ($students as $id=>$studentE){
                $factors = $studentE->sum("factor");
                $wightedScores = $studentE->sum("wightedScore");
                $average = round( $wightedScores / $factors,2);
                $result[$id]['average'] = $average;
                $result[$id]['scores'] = $studentE;
            }

            return $result;

        }



        return $result;
    }
}
