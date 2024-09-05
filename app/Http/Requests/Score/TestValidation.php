<?php

namespace App\Http\Requests\Score;

use Illuminate\Foundation\Http\FormRequest;

class TestValidation extends FormRequest
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
            'classroom_id'=>'required|exists:classrooms,id',
            'title'=>'nullable|string|min:2|max:50',
            'status'=>'nullable|boolean',

            'courses'=>'required|array|min:1',
            'courses.*.course_id'=>'required|exists:courses,id',
            'courses.*.expected'=>'nullable|numeric|min:0|max:100',
            'courses.*.average'=>'nullable|numeric|min:0|max:100',
            'courses.*.contents'=>'required|array|min:1',
            'courses.*.contents.*'=>'required|exists:contents,id',
        ];
    }
}
