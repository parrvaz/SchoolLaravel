<?php

namespace App\Http\Controllers;

use App\Http\Requests\Plan\StudentPlanValidation;
use App\Http\Resources\Plan\StudentPlanCollection;
use App\Http\Resources\Plan\StudentPlanResource;
use App\Models\Student;
use App\Models\StudentPlan;
use Illuminate\Http\Request;

class StudentPlanController extends Controller
{
    public function store(Request $request, StudentPlanValidation $validation){

        $plan = StudentPlan::create([
            'student_id' => $validation->student_id,
            'date' => $validation->date,
            'course_id' => $validation->course_id,
            'minutes' => $validation->minutes,
        ]);

        return new StudentPlanResource($plan);
    }

//    public function show(Request $request){
//        return new StudentPlanCollection(
//            Student::whereHas('classroom', function($query) use($request) {
//                return $query->where('user_grade_id', $request->userGrade->id);
//        })->paginate(config('constant.bigPaginate')));
//    }

    public function showSingle(StudentPlan $plan){
        return new StudentPlanResource($plan);
    }

    public function update(StudentPlanValidation $validation, StudentPlan $plan){
        $plan->update([
            'date' => $validation->date,
            'course_id' => $validation->course_id,
            'minutes' => $validation->minutes,
        ]);
        return new StudentPlanResource($plan);
    }

    public function delete(StudentPlan $plan){
        $plan->delete();
        return $this->successMessage();
    }
}
