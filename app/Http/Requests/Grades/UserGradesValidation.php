<?php

namespace App\Http\Requests\Grades;

use Illuminate\Foundation\Http\FormRequest;

class UserGradesValidation extends FormRequest
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
            'title'=>'required|string|min:2|max:50',
        ];
    }
}
