<?php

namespace App\Http\Controllers;

use App\Exports\AbsentsExport;
use App\Exports\CardExport;
use App\Exports\GeneralExcelExport;
use App\Http\Requests\Report\FilterValidation;
use App\Http\Resources\Reports\AbsentsReportCollection;
use App\Http\Resources\Reports\Card\CardResource;
use App\Http\Resources\Reports\Card\CardSeparateCollection;
use App\Http\Resources\Reports\NumberExamsReportCollection;
use App\Http\Resources\Reports\Progress\ProgressCollection;
use App\Models\Absent;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentExam;
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
        return $result["students"];
        if (!$validation->isSeparate)
            return new CardResource($result);
        else
        {
           return new CardSeparateCollection($result["students"]);
        }

    }

    public function cardExcel(Request $request,FilterValidation $validation){
        $validation['isSeparate'] = 1;
        $result = $this->cardMtd($request,$validation);
        $courseNames = Course::whereIn("id",$result["courses"])->get();
        $result["courses"] = $courseNames;
        return Excel::download(new CardExport($result), "کارنامه".".xlsx");
    }

    public function cardPdf(Request $request,FilterValidation $validation){
        $result = $this->cardMtd($request,$validation);


        if (!$validation["isSeparate"]){
            $header = collect([
                'title' => 'کارنامه ماهانه',
                'school' => 'مدرسه احمدی روشن',
                'month' => 'مهر',
                'grade' => 'دهم',
                'year' => '۱۴۰۳ - ۱۴۰۴',
                'studentName' => 'کل',
                'average' => $result["average"],
            ]);

            return $this->pdfStuff('cardAll',$header,$result["studentExam"]);
        }else{
            $header = collect([
                'title' => 'کارنامه ماهانه',
                'school' => 'مدرسه احمدی روشن',
                'month' => 'مهر',
                'grade' => 'دهم',
                'year' => '۱۴۰۳ - ۱۴۰۴',
//                'studentName' => 'کل',
//                'average' => $result["average"],
            ]);

            return $this->pdfStuff('cardSeparate',$header,$result["students"]);
        }

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
        $this->generalFilter($exams, $validation);

        $exams =  $exams->orderBy("date")
            ->groupBy("exams.date")
            ->select(
                "exams.date",
                DB::raw("MIN(exams.id) as id"),
                DB::raw("ROUND( SUM((student_exam.scaledScore /5 ) * course_fields.factor) / SUM(course_fields.factor)  ,2) as score"),
                DB::raw("ROUND(AVG( ROUND((exams.expected/exams.totalScore)*20,2 ) ),2) as expected"),
                DB::raw("GROUP_CONCAT(DISTINCT exams.course_id) as course_ids") // اضافه کردن course_ids

            );

        $exams= $exams->get();

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
            $classExam = $this->globalFilterWhereIn($classExam,"exams.id",$validation->exams);

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

    public function numberExams(Request $request,FilterValidation $validation){

        $counts = DB::table('student_exam')
            ->join('exams', 'student_exam.exam_id', '=', 'exams.id')
            ->select('exams.course_id', DB::raw('COUNT(student_exam.id) as total_scores'))
            ->groupBy('exams.course_id')
            ->get();

// گرفتن کمترین، بیشترین و میانگین تعداد نمرات
        $statistics = DB::table('student_exam')
            ->join('exams', 'student_exam.exam_id', '=', 'exams.id')
            ->select(
                DB::raw('MIN(counts.total_scores) as min_scores'),
                DB::raw('MAX(counts.total_scores) as max_scores'),
                DB::raw('AVG(counts.total_scores) as avg_scores')
            )
            ->fromSub(function ($query) {
                $query->from('student_exam')
                    ->join('exams', 'student_exam.exam_id', '=', 'exams.id')
                    ->select('exams.course_id', DB::raw('COUNT(student_exam.id) as total_scores'))
                    ->groupBy('exams.course_id');
            }, 'counts')
            ->first();

        return $statistics;







        $exams = Student::query()
            ->whereHas('classroom', function($query) use($request) {
                return $query->where('user_grade_id', $request->userGrade->id);
            })
            ->rightJoin('student_exam',"students.id","student_exam.student_id" )
            ->join("exams","student_exam.exam_id","exams.id")
            ->where("exams.status",1)

        ;


        $this->generalFilter($exams, $validation);
        $exams = $exams->groupBy("student_id","course_id")
            ->select("student_id"
                ,"course_id"
                ,DB::raw("count(*) as count")
            );


        $exams = $exams->get()->groupBy("course_id");


        return $exams;

       return new NumberExamsReportCollection($exams);

    }



    public function generalExcel(Request $request,FilterValidation $validation){
        $exams= Exam::where("exams.user_grade_id", $request->userGrade->id)
            ->where("exams.status",1)
            ->orderBy("exams.course_id")
            ->orderBy("date")
            ->get();

        $students = StudentExam::
            whereHas('exam', function ($query)use($request) {
                return $query->where('user_grade_id', $request->userGrade->id);
            })
            ->orderBy("student_id")
            ->get()
            ->groupBy("student_id")
        ;

        $items = $this->makeItemsForExcel($exams,$students);


        $validation->title = $validation->title ?? "اکسل کامل";
        $name = $validation->title  . ".xlsx" ;
        return Excel::download(new GeneralExcelExport($items), $name);
    }





}
