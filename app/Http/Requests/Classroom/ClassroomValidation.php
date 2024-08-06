<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomValidation extends FormRequest
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
            'title'=>'required|string|min:2|max:50',
            'field_id' => 'required|exists:fields,id',
            'number'=>'nullable|string|min:1|max:50',
            'floor'=>'nullable|string|min:1|max:50',
        ];
    }
}
