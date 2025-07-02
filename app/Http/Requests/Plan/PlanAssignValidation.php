<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanAssignValidation extends FormRequest
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
            "data"=>'nullable|array|min:0',
            "data.*.id"=>'nullable|exists:plans,id',
            "data.*.classroom_id"=>'nullable|exists:classrooms,id',
            "data.*.isDuplicate"=>'nullable|boolean',
            "data.*.title"=>'required|string|min:1|max:100',
            "data.*.students"=>'nullable|array|min:0',
            "data.*.students.*.id"=>'required|exists:students,id',
//            "data.*.students.*.label"=>'required|string|min:1|max:100',


        ];
    }
}
