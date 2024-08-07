<?php

namespace App\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;

class StudentTestValidation extends FormRequest
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
            'students'=>'required|array|min:1',
            'students.*.student_id'=>'required|exists:students,id',
//            'students.*.description'=>'nullable|string|min:2|max:50',
            'students.*.scores'=>'required|array|min:1',
            'students.*.scores.*.test_course_id'=>'required|exists:test_courses,id',
            'students.*.scores.*.score'=>'required|numeric|min:0|max:100',
            'students.*.scores.*.balance'=>'nullable|numeric|min:0|max:100000',
        ];
    }
}
