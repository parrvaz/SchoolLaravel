<?php

namespace App\Http\Controllers;


use App\Models\Plan;
use App\Models\StudyPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Morilog\Jalali\Jalalian;

class GradeController extends Controller
{

    public function operation(Request $request){

//        $nowInTehran = Carbon::now('Asia/Tehran');
//        $jalaliDate = Jalalian::fromCarbon($nowInTehran)->getDayOfWeek();
//        $date = Jalalian::fromCarbon(Carbon::yesterday('Asia/Tehran'));
//
//        $allPlanStudents = Plan::with("students")
//            ->with(['coursePlans' => function ($query) use ($jalaliDate) {
//                $query->where('day', $jalaliDate);
//            }])
//            ->get();
//
//
//            $data = [];
//            foreach ($allPlanStudents as $item) {
//
//
//                foreach ($item->students as &$itemStd) {
//                    $planCourses = $item->coursePlans;
//                    foreach ($planCourses as $planCours) {
//                        $data[] = [
//                            'student_id' => $itemStd->id,
//                            'course_id' => $planCours->course_id,
//                            "date" => $date,
//                            "start" => $planCours->start,
//                            "end" => $planCours->end,
//                        ];
//                    }
//                }
//
//                if (count($data) > 300)
//                {
//                    StudyPlan::insert($data);
//                    $data=[];
//                }
//
//
//            }
//
//            StudyPlan::insert($data);


    }


}
