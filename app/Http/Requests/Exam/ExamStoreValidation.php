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
            'classroom_id'=>'nullable|exists:classrooms,id',
            'expected'=>'nullable|numeric|min:1|max:100',
            'totalScore'=>'nullable|numeric|min:1|max:100',
            'isFinal'=>'nullable|boolean',
            'type'=>'nullable|in:1,2,3,4',//1:katbi 2:shafahi 3:testi 4:homework
            'isGeneral'=>'nullable|boolean',

            'contents'=>'nullable|array',
            'contents.*'=>'required|exists:contents,id',

            'classrooms'=>'nullable|array',
            'classrooms.*'=>'required|exists:classrooms,id',

            'students'=> request()->isFinal ?   'required|array|min:1' :'nullable|array' ,
            'students.*.student_id'=>'required|exists:students,id',
            'students.*.score'=>'nullable|numeric|min:0|max:100',
            'students.*.isPresent'=>'nullable|boolean',
        ];
    }
}
