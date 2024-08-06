<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ExamValidation extends FormRequest
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
            'date'=>'required|date',
            'course_id'=>'required|exists:courses,id',
            'classroom_id'=>'required|exists:classrooms,id',
            'expected'=>'nullable|numeric|min:0|max:100',
            'totalScore'=>'required|numeric|min:1|max:100',

            'contents'=>'required|array|min:1',
            'contents.*'=>'required|exists:contents,id',

            'students'=>'required|array|min:1',
            'students.*.student_id'=>'required|exists:students,id',
            'students.*.score'=>'required|numeric|min:0|max:100',
            'students.*.description'=>'nullable|string|min:2|max:50',
        ];
    }
}
