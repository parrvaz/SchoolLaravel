<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateValidation extends FormRequest
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
            'firstName'=>'required|string|min:2|max:50',
            'lastName'=>'required|string|min:2|max:50',
            'nationalId'=>'required|digits:10',
            'degree'=>'nullable|string|min:2|max:50',
            'personalId'=>'nullable|digits:10',
            'phone'=>'required|digits:11|unique:teachers,phone,'.$this->teacher->id,
            'isAssistant'=>'required|boolean'
        ];
    }
}