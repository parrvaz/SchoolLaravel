<?php

namespace App\Http\Requests\Report;

use App\Rules\JalaliDateValidation;
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
        $this->merge(['isSeparate' => filter_var($this->input('isSeparate'), FILTER_VALIDATE_BOOLEAN)]);
        return [
            'isSeparate'=>'nullable|boolean',
            'courses'=>'nullable|array',
            'courses.*'=>'exists:courses,id',
            'classrooms'=>'nullable|array',
            'classrooms.*'=>'nullable|exists:classrooms,id',
            'students'=>'nullable|array',
            'students.*'=>'nullable|exists:students,id',
            'types'=>'nullable|array',
            'types.*'=>'nullable|in:1,2,3,4',
            'exams'=>'nullable|array',
            'exams.*'=>'nullable|exists:exams,id',
            'title'=>'nullable|string|max:100',

            'date'=>['nullable', new JalaliDateValidation()],
            'startDate'=>['nullable', new JalaliDateValidation()],
            'endDate'=>['nullable', new JalaliDateValidation()],

            'card'=>'nullable|boolean',
            'absent'=>'nullable|boolean',
            'detail'=>'nullable|boolean',
            'absentNumber'=>'nullable|boolean',
            'absentTotal'=>'nullable|boolean',
            'absentPercent'=>'nullable|boolean',



        ];
    }
}
