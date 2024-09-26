<?php

namespace App\Http\Requests\Exam;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class ExamStoreValidation extends FormRequest
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
            'date'=>['required', new JalaliDateValidation()],
            'course_id'=>'required|exists:courses,id',
            'classroom_id'=>'required|exists:classrooms,id',
            'expected'=>'nullable|numeric|min:0|max:100',
            'totalScore'=>'nullable|numeric|min:0|max:100',
            'isFinal'=>'nullable|boolean',
            'type'=>'nullable|in:1,2,3',//1:katbi 2:shafahi 3:testi
            'isGeneral'=>'nullable|boolean',

            'contents'=>'nullable|array',
            'contents.*'=>'required|exists:contents,id',

            'students'=>'nullable|array',
            'students.*.student_id'=>'required|exists:students,id',
            'students.*.score'=>'required|numeric|min:0|max:100',
        ];
    }
}
