<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StudyStoreValidation extends FormRequest
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
            'date' => 'required|string',
            'course_id'=>'required|exists:courses,id',

//            "start"=>["required"],
//            "end"=>["required"],
//            "plan"=>'required|array|min:1',
//
//            'plan.*.isFix'=>'required|boolean',
//            'plan.*.id'=>'nullable',
//            'plan.*.date' => 'required|string',
//            'plan.*.course_id'=>'required|exists:courses,id',
        ];
    }
}
