<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanValidation extends FormRequest
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
            'title'=>'required|string|min:1|max:100',
            'plan'=>'required|array|min:1',
            "plan.*.course_id"=>'required|exists:courses,id',
            "plan.*.day"=>'required|string',
            "plan.*.start"=>'required',
//            "plan.*.start"=>'required|date_format:H:i',
//            "plan.*.end"=>'required|date_format:H:i',
            "plan.*.end"=>'required',

        ];
    }
}
