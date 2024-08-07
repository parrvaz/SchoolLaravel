<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class StudentPlanValidation extends FormRequest
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
            'student_id'=>'required|exists:students,id',
            'date' => 'required|date',
            'course_id'=>'required|exists:courses,id',
            'minutes'=>'required|numeric|min:0|max:5000',
        ];
    }
}
