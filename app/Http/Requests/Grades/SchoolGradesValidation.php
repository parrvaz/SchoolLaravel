<?php

namespace App\Http\Requests\Grades;

use Illuminate\Foundation\Http\FormRequest;

class SchoolGradesValidation extends FormRequest
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
            'grade_id' => 'nullable|exists:grades,id',
            'school_id' => 'nullable|exists:schools,id',
            'purchasedStudents' => 'nullable|integer|min:0|max:9000',
        ];
    }
}
