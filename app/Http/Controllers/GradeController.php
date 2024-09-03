<?php

namespace App\Http\Controllers;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Score\ScoreCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function allExamShow(Request $request){
        $userGrade = $request['userGrade'];

        $exams = DB::table('exams')
            ->where('user_grade_id',$userGrade->id);
        $classScores = DB::table('class_scores')
            ->where('user_grade_id',$userGrade->id)
            ->union($exams);

        return new ScoreCollection( $classScores->paginate($request->per_page ?? 15) );

    }
}
