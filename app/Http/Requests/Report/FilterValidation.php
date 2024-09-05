<?php

namespace App\Http\Requests\Report;

use App\Rules\FilterListRule;
use Illuminate\Foundation\Http\FormRequest;

class FilterValidation extends FormRequest
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
            'course_id'=>'nullable|exists:courses,id',
            'classroom_id'=>'nullable|exists:classrooms,id',
            'teacher_id'=>'nullable|exists:teachers,id',
            'student_id'=>'nullable|exists:students,id',
        ];
    }
}
