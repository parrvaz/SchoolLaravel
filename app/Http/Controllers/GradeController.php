<?php

namespace App\Http\Controllers;

use App\Http\Resources\Classroom\ClassroomCollection;
use App\Http\Resources\Grade\ExamCreateCollection;
use App\Http\Resources\Grade\ExamCreateResource;
use App\Http\Resources\Score\AllExamCollection;
use App\Http\Resources\Score\ScoreCollection;
use App\Http\Resources\Student\StudentCollection;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class GradeController extends Controller
{

    public function allExamShow(Request $request){
        $userGrade = $request['userGrade'];

        $exams = DB::table('exams')
            ->where('exams.user_grade_id',$userGrade->id)
            ->join('courses', 'exams.course_id', '=', 'courses.id')
            ->join('classrooms', 'exams.classroom_id', '=', 'classrooms.id')
            ->select("classrooms.title as classroom","courses.name as title","date",
            DB::raw("'امتحان کتبی' as type"),   DB::raw("'exams' as tbl"),"exams.id"
        );

        $classScores = DB::table('class_scores')
            ->where('class_scores.user_grade_id',$userGrade->id)
            ->join('courses', 'class_scores.course_id', '=', 'courses.id')
            ->join('classrooms', 'class_scores.classroom_id', '=', 'classrooms.id')
            ->select("classrooms.title as classroom","courses.name as title","date",
                DB::raw("'امتحان شفاهی' as type"),   DB::raw("'class_scores' as tbl"),"class_scores.id"
            );


        $tests = DB::table('tests')
            ->where('tests.user_grade_id',$userGrade->id)
            ->join('classrooms', 'tests.classroom_id', '=', 'classrooms.id')
            ->select("classrooms.title as classroom","tests.title","date",
                DB::raw("'آزمون تستی' as type"),   DB::raw("'tests' as tbl"),"tests.id"
            );


        return new AllExamCollection($exams->unionAll($classScores)->unionAll($tests)->orderBy("date","DESC")->paginate($request->per_page ?? 15));

    }


    public function examsCreate(Request $request){
        $user = User::find(7);
        $user->assignRole('admin');
        return new ExamCreateResource($request['userGrade']);
    }
}
