<?php

namespace App\Http\Controllers;

use App\Http\Resources\Plan\StudyResource;
use App\Models\Plan;
use App\Models\StudyPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class StudyController extends Controller
{

    public function show(Request $request){
        $student = auth()->user()->student;
        $allItems =[];


        $threeWeeksAgo = Carbon::now()->subWeeks(3);
        $studyPlans = StudyPlan::where("student_id",$student->id)->where('date', '>=', $threeWeeksAgo)->get();
        foreach ($studyPlans as $item){
            $allItems[]=[
                'id' =>$item->id,
                'title' => $item->course->name,
                'course_id' => $item->course_id,
                "date"=>$this->makeDateString($item,self::gToJ($item->date)) ,
                "isFix"=>true,
            ];
        }

        $plan= $student->plan->first();
        $planCourses = $plan->coursePlans;

        foreach ($planCourses as $planItem ){
            $allItems[]=[
                'id' =>$planItem->id,
                'title' => $planItem->course->name,
                'course_id' => $planItem->course_id,
                "date"=>$this->makeDateString($planItem,$this->findDate($planItem)->format("Y/m/d")) ,
                "isFix"=>true,
            ];
        }

        $plan->allItems= $allItems;

        return new StudyResource($plan);
    }

    public function store(Request $request,StudyReaquest $validation){

    }


//    public function studyPlanStore(){
//
//        $today = Carbon::now()->format("Y/m/d");
//
//        $plans = Plan::with("students")->with("coursePlans")->get();
//
//        foreach ($plans as $plan){
//            foreach ($plan->students as $std){
//                return $plan;
//            }
//        }
//
//        return $plans;
//
//        StudyPlan::insert([
//
//        ]);
//
//    }

    private function makeDateString($item,$date){
        $dateString = $date . ' '
            . \Carbon\Carbon::createFromFormat('H:i:s', $item->start)->format('H:i')
            . '-' . \Carbon\Carbon::createFromFormat('H:i:s', $item->end)->format('H:i');

        return $dateString;
    }

    private function findDate($item) {
        $today = Jalalian::now();
        // دریافت روز فعلی هفته (به میلادی)
        $currentWeekDay = $today->getDayOfWeek() +1;
        // اختلاف روز فعلی با روزی که داریم
        $dayDifference = $item->day - $currentWeekDay;
        // تنظیم تاریخ نهایی با اضافه کردن اختلاف روز
        $targetDate = $today->addDays($dayDifference);
        return $targetDate;

    }

}
