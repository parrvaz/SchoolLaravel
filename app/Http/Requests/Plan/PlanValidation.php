<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class   PlanValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {

        return [
            'title'=>'required|string|min:1|max:100|unique:plans,title',
            'classroom_id'=>'required|exists:classrooms,id',
            'plan'=>'required|array|min:1',
            "plan.*.course_id"=>'required|exists:courses,id',
            "plan.*.day"=>'required|string',
            "plan.*.time"=>'required|numeric|min:1|max:1200',

            "students"=>'nullable|array|min:0',
            "students.*"=>['required','exists:students,id',"unique:plan_student,student_id"],

//            "plan.*.start"=>'required',
//            "plan.*.end"=>'required',

        ];
    }
}
