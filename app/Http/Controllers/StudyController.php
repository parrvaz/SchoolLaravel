<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\StudyStoreValidation;
use App\Http\Requests\Report\FilterValidation;
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

    public function show(Request $request,FilterValidation $validation){
        $student = auth()->user()->student;
        return $this->showMtd($student,$validation);
    }

    public function store(Request $request,StudyStoreValidation $validation){
        $student = auth()->user()->student;
        return $this->storeMtd($validation,$student);
    }

    public function delete($schoolGrade,Study $study){
        $study->delete();
        return $this->successMessage();
    }


    public function showStudent($schoolGrade,Student $student,FilterValidation $validation){
        return $this->showMtd($student,$validation);
    }

    public function storeStudent(StudyStoreValidation $validation,$schoolGrade,Student $student){
        return $this->storeMtd($validation,$student);
    }

    private function showMtd($student,$validation){


        $allItems =[];

        //present Fix
        $plan= $student->plan->first();

        if ($plan!=null) {
            $planCourses = $plan->coursePlans;
            foreach ($planCourses as $planItem) {
                $allItems[] = [
                    'id' => $planItem->id,
                    'title' => $planItem->course->name,
                    'course_id' => $planItem->course_id,
                    "date" => $this->findDateWithFilter($planItem,$validation->startDate),
                    "time" => $planItem->time,
                    "isFix" => true,
                ];
            }
        }else{
            $plan = new Plan();
        }

        //past Fix
//        $threeWeeksAgo = Carbon::now()->subWeeks(1);
//        $studyPlans = StudyPlan::where("student_id",$student->id)->where('date', '>=', $threeWeeksAgo)->get();
//        foreach ($studyPlans as $item){
//            $allItems[]=[
//                'id' =>$item->id,
//                'title' => $item->course->name,
//                'course_id' => $item->course_id,
//                "date"=>$this->makeDateString($item,self::gToJ($item->date)) ,
//                "isFix"=>true,
//            ];
//        }



        //studies
        $study = null;
        if ($validation->startDate != null && $validation->endDate != null){
            $study = Study::where("student_id",$student->id)->whereBetween('date', [ self::Jtog($validation->startDate) , self::Jtog($validation->endDate)])->get();
        }else
        {
            $threeWeeksAgo = Carbon::now()->subWeeks(1);
            $study = Study::where("student_id",$student->id)->where('date', '>=', $threeWeeksAgo)->get();
        }

        foreach ($study as $studyItem){
            $allItems[]=[
                'id' =>$studyItem->id,
                'title' => $studyItem->course->name,
                'course_id' => $studyItem->course_id,
                "date"=>self::gToJ($studyItem->date) ,
                "time"=>$studyItem->time ,
                "isFix"=>false,
            ];
        }


        $plan->allItems= $allItems;
        return new StudyResource($plan);

    }


    private function storeMtd($validation,$student){
        $dateG = self::jToG($validation["date"]);

        //delete old study if exists
        $oldStudy = Study::where("date",$dateG)->where("course_id",$validation["course_id"]);
        if ($oldStudy->count() > 0)
            $oldStudy->delete();


        $now = Carbon::now()->endOfWeek();
        if ($now->lt($dateG))
            return $this->error("dateLtNow");

        $study = Study::create(
            [
                "student_id" => $student->id,
                "course_id" => $validation["course_id"],
                "date" => $dateG,
                "time" => $validation['time'],
            ]
        );

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
        return $targetDate->format("Y/m/d");

    }



    //temporary
    private function findDateWithFilter($item,$startDate) {
        if ($startDate == null)
            return $this->findDate($item);

        // ساخت شیء جلالی از تاریخ شروع هفته
        $startDate = Jalalian::fromFormat('Y/m/d', $startDate);

        // محاسبه روز مورد نظر
        $targetDate = $startDate->addDays($item->day - 1);

        // بازگرداندن تاریخ شمسی به فرمت YYYY-MM-DD
        return $targetDate->format('Y/m/d');

    }

}
