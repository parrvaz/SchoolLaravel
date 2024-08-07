<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\PlanValidation;
use App\Http\Resources\Plan\PlanCollection;
use App\Http\Resources\Plan\PlanResource;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function store(Request $request, PlanValidation $validation){

            $plan = Plan::create([
                'user_grade_id' => $request['userGrade']->id,
                'classroom_id' => $validation->classroom_id,
                'date' => $validation->date,
                'course_id' => $validation->course_id,
                'minutes' => $validation->minutes,
            ]);

            return new PlanResource($plan);
    }

    public function show(Request $request){
        return new PlanCollection($request['userGrade']->plans()->paginate(config('constant.bigPaginate')));
    }

    public function showSingle(Plan $plan){
        return new PlanResource($plan);
    }

    public function update(PlanValidation $validation, Plan $plan){
            $plan->update([
                'classroom_id' => $validation->classroom_id,
                'date' => $validation->date,
                'course_id' => $validation->course_id,
                'minutes' => $validation->minutes,
            ]);
            return new PlanResource($plan);
    }

    public function delete(Plan $plan){
        $plan->delete();
        return $this->successMessage();
    }

}
