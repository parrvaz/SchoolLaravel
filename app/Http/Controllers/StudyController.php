<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\StudyStoreValidation;
use App\Http\Resources\Bell\BellCollection;
use App\Http\Resources\Plan\StudyCourseResource;
use App\Http\Resources\Plan\StudyResource;
use App\Models\Plan;
use App\Models\Student;
use App\Models\Study;
use App\Models\StudyPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class StudyController extends Controller
{

    public function show(Request $request){
        $student = auth()->user()->student;
        return $this->showMtd($student);
    }

    public function store(Request $request,StudyStoreValidation $validation){
        $student = auth()->user()->student;

        return $this->storeMtd($validation,$student);
    }

    public function delete($userGrade,Study $study){
        $study->delete();
        return $this->successMessage();
    }


    public function showStudent($userGrade,Student $student){
        return $this->showMtd($student);
    }

    public function storeStudent(StudyStoreValidation $validation,$userGrade,Student $student){
        return $this->storeMtd($validation,$student);
    }

    private function showMtd($student){
        $allItems =[];

        //present Fix
        $plan= $student->plan->first();
        if ($plan==null)
            return $this->error("haveNotPlan");
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


        //past Fix
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



        //studies
        $study = Study::where("student_id",$student->id)->where('date', '>=', $threeWeeksAgo)->get();

        foreach ($study as $studyItem){
            $allItems[]=[
                'id' =>$studyItem->id,
                'title' => $studyItem->course->name,
                'course_id' => $studyItem->course_id,
                "date"=>$this->makeDateString($studyItem,self::gToJ($studyItem->date)) ,
                "isFix"=>false,
            ];
        }


        $plan->allItems= $allItems;
        return new StudyResource($plan);

    }


    private function storeMtd($validation,$student){
        $space = explode(" ", $validation["date"]);
        $dash = explode("-", $space[1]);
        $study = Study::create(
            [
                "student_id" => $student->id,
                "course_id" => $validation["course_id"],
                "date" => self::jToG($space[0]),
                "start" => $dash[0],
                "end" => $dash[1],
            ]
        );

        $study->dateStr = $validation["date"];

        return (new StudyCourseResource($study))
            ->additional(['message' => "با موفقیت ثبت شد"]);
    }




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

//    private function deleteItems($validation,$stdId)
//    {
//        Study::where("student_id",$stdId)->whereBetween('date', [$validation[""], $ageTo])
//
//    }

//    public function store(Request $request,StudyStoreValidation $validation){
//
//        return DB::transaction(function () use($validation) {
//            $student = auth()->user()->student;
//
//            $this->deleteItems($validation,$student->id);
//
//
//            $items = [];
//            foreach ($validation->plan as $planItem) {
//                if (!$planItem["isFix"] && ($planItem["id"] ?? null) == null) {
//                    $space = explode(" ", $planItem["date"]);
//                    $dash = explode("-", $space[1]);
//                    $items[] = [
//                        "student_id" => $student->id,
//                        "course_id" => $planItem["course_id"],
//                        "date" => self::jToG($space[0]),
//                        "start" => $dash[0],
//                        "end" => $dash[1],
//                    ];
//                }
//            }
//
//            Study::insert($items);
//
//            return $this->successMessage();
//        });
//    }


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

}
