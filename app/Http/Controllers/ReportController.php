<?php

namespace App\Http\Controllers;

use App\Http\Resources\Reports\ExamCountCollection;
use App\Models\Exam;
use App\Sale;
use Database\Factories\ExamFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function allExamCount(Request $request){
        $userGrade = $request['userGrade'];

        $exams =  Exam::query()
            ->where('exams.user_grade_id',$userGrade->id)
            ->join('courses', 'exams.course_id', '=', 'courses.id')
//            ->join('classrooms', 'exams.classroom_id', '=', 'classrooms.id')
            ->groupBy("courses.id", "courses.title")
            ->select(
                "courses.id",
                "courses.title",
                DB::raw("COUNT(exams.id) as exam_count",

                ))->get();

        return new ExamCountCollection($exams);
    }
}
