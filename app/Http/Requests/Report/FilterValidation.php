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
        return [
            'courses'=>'nullable|array',
            'courses.*'=>'exists:courses,id',
            'classrooms'=>'nullable|array',
            'classrooms.*'=>'nullable|exists:classrooms,id',
            'students'=>'nullable|array',
            'students.*'=>'nullable|exists:students,id',
            'types'=>'nullable|array',
            'types.*'=>'nullable|in:1,2,3',
            'exams'=>'nullable|array',
            'exams.*'=>'nullable|exists:exams,id',


            'date'=>['nullable', new JalaliDateValidation()],
            'startDate'=>['nullable', new JalaliDateValidation()],
            'endDate'=>['nullable', new JalaliDateValidation()],
            'isSeparate'=>'nullable|boolean',
        ];
    }
}
