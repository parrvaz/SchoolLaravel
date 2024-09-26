<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\PlanAssignValidation;
use App\Http\Requests\Plan\PlanValidation;
use App\Http\Resources\Plan\PlanCollection;
use App\Http\Resources\Plan\PlanResource;
use App\Models\Bell;
use App\Models\CoursePlan;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function store(Request $request, PlanValidation $validation){
        return DB::transaction(function () use($request,$validation) {

            $plan = Plan::create([
                'user_grade_id' => $request->userGrade->id,
                'title' => $validation->title,
            ]);

            $items = $this->makeItems($validation, $plan->id);
            $coursePlans = CoursePlan::insert($items);
            return $this->successMessage();
        });
    }

    public function update(PlanValidation $validation,$userGrade, Plan $plan){
        return DB::transaction(function () use($plan,$validation) {

            $plan->update([
                'title' => $validation->title,
            ]);

            $plan->coursePlans()->delete();

            $items = $this->makeItems($validation, $plan->id);
            $coursePlans = CoursePlan::insert($items);
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
        return new PlanCollection($request->userGrade->plans()->get());
    }

    public function showSingle($userGrade,Plan $plan){
        return new PlanResource($plan);
    }



    public function delete($userGrade,Plan $plan){
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
                'start' =>$item['start'],
                'end' => $item['end'],
            ];
        }

        return $items;
    }

    private function duplicate($planId,$validation,$request){
        $plan = Plan::find($planId);
        $newPlan = Plan::create([
            'user_grade_id' => $request->userGrade->id,
            'title' => $validation['title'],
            'classroom_id' => $validation['classroom_id'],
        ]);

        $items=[];
        foreach ($plan->coursePlans as $item){
            $items[] = [
                "plan_id"=> $newPlan->id,
                'course_id' => $item['course_id'],
                'day' =>$item['day'],
                'start' =>$item['start'],
                'end' => $item['end'],
            ];
        }

        $coursePlans = CoursePlan::insert($items);
        return $newPlan;
    }

    private function deletePlans(Request $request ,$validation)
    {
        $oldIds =  Plan::where("user_grade_id",$request->userGrade->id)->pluck("id")->toArray();
        $newIds = array_unique(Arr::pluck($validation->data,"id" ));
        $delteIds = array_values( array_diff_key($oldIds,$newIds));

        Plan::whereIn("id",$delteIds)->delete();
    }

}
