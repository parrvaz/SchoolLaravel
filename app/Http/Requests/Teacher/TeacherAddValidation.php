<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAddValidation extends FormRequest
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
            'nationalId'=>'required|digits:10|exists:teachers,nationalId',
            'personalId'=>'nullable|string',
        ];
    }

    public function messages(): array

    {

        return [

            'nationalId.exists' => 'دبیری با این شماره ملی تاکنون در سیستم ثبت نشده است',

        ];

    }
}
