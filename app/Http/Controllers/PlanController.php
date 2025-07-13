<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\PlanAssignValidation;
use App\Http\Requests\Plan\PlanUpdateValidation;
use App\Http\Requests\Plan\PlanValidation;
use App\Http\Resources\Plan\PlanCollection;
use App\Http\Resources\Plan\PlanResource;
use App\Models\Bell;
use App\Models\CoursePlan;
use App\Models\Plan;
use App\Models\PlanStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function store(Request $request, PlanValidation $validation){
        return DB::transaction(function () use($request,$validation) {

            $plan = Plan::create([
                'school_grade_id' => $request->schoolGrade->id,
                'classroom_id' => $validation->classroom_id ?? null,
                'title' => $validation->title,
            ]);

            $items = $this->makeItems($validation, $plan->id);
            $coursePlans = CoursePlan::insert($items);


            $plan->students()->attach($validation->students);

            return $this->successMessage();
        });
    }

    public function update(PlanUpdateValidation $validation,$schoolGrade, Plan $planModel){
        return DB::transaction(function () use($planModel,$validation) {

            $plan_student =  PlanStudent::where("plan_id","!=",$planModel->id)->whereIn("student_id",$validation->students)->get();
            if (count($plan_student)!= 0)
                return response()->json([
                    'message' =>  "برای دانش آموز ". $plan_student->first()->student->name ." برنامه مطالعاتی دیگری ثبت شده است",
                    'status' => 'error',
                ], 404);


            $planModel->coursePlans()->delete();
            $planModel->students()->detach();


            $planModel->update([
                'title' => $validation->title,
                'classroom_id' => $validation->classroom_id ?? $planModel->classroom_id,
            ]);


            $items = $this->makeItems($validation, $planModel->id);
            $coursePlans = CoursePlan::insert($items);

            $planModel->students()->attach($validation->students);


            return $this->successMessage();
        });
    }

    public function assign(Request $request,PlanAssignValidation $validation){
        return DB::transaction(function () use($request,$validation) {
            $this->deletePlans($request,$validation);
            foreach ($validation->data as $plan) {
                $planModel = null;
                if ($plan['isDuplicate'] ?? false) {
                    //duplicate
                    $planModel = $this->duplicate($plan["id"], $plan, $request);
                } else {
                    //original
                    $planModel = Plan::find($plan["id"]);
                    $planModel->update([
                        "classroom_id" => $plan["classroom_id"]
                    ]);
                    //delete old students
                    $planModel->students()->detach();
                }

                $planModel->students()->attach(Arr::pluck($plan["students"], "id"));
            }

            return $this->successMessage();
        });

    }

    public function show(Request $request){
        return new PlanCollection($request->schoolGrade->plans()->get());
    }

    public function showSingle($schoolGrade,Plan $plan){
        return new PlanResource($plan);
    }



    public function delete($schoolGrade,Plan $plan){
        $plan->delete();
        return $this->successMessage();
    }


    private function makeItems($validation,$planId){

        $daysOfWeek = [
            'sat' => 1, // شنبه
            'sun' => 2, // یکشنبه
            'mon' => 3, // دوشنبه
            'tue' => 4, // سه‌شنبه
            'wed' => 5, // چهارشنبه
            'thu' => 6, // پنج‌شنبه
            'fri' => 7  // جمعه (اختیاری)
        ];

        $items=[];
        foreach ($validation->plan as $item){
            $items[] = [
                "plan_id"=> $planId,
                'course_id' => $item['course_id'],
                'day' => $daysOfWeek[$item['day']],
                'time' =>$item['time'],

            ];
        }

        return $items;
    }

    private function duplicate($planId,$validation,$request){
        $plan = Plan::find($planId);
        $newPlan = Plan::create([
            'school_grade_id' => $request->schoolGrade->id,
            'title' => $validation['title'],
            'classroom_id' => $plan['classroom_id'],
        ]);

        $items=[];
        foreach ($plan->coursePlans as $item){
            $items[] = [
                "plan_id"=> $newPlan->id,
                'course_id' => $item['course_id'],
                'day' =>$item['day'],
                'time' =>$item['time'],
//                'start' =>$item['start'],
//                'end' => $item['end'],
            ];
        }

        $coursePlans = CoursePlan::insert($items);
        return $newPlan;
    }

    private function deletePlans(Request $request ,$validation)
    {
        $oldIds =  Plan::where("school_grade_id",$request->schoolGrade->id)->pluck("id")->toArray();
        $newIds = array_unique(Arr::pluck($validation->data,"id" ));
        $delteIds = array_values( array_diff_key($oldIds,$newIds));

        Plan::whereIn("id",$delteIds)->delete();
    }

}
