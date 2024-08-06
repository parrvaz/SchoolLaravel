<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseValidation extends FormRequest
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
            'list'=>'required|array|min:1',
            'list.*.course_id'=>'required|exists:courses,id',
            'list.*.classroom_id'=>'required|exists:classrooms,id',
            'list.*.teacher_id'=>'required|exists:teachers,id',
        ];
    }
}
