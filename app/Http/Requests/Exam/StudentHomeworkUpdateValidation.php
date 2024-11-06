<?php

namespace App\Http\Requests\Exam;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudentHomeworkUpdateValidation extends FormRequest
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
            'note' => 'nullable|string|max:240',
            'pdf' => 'required|mimes:pdf|max:5120',
        ];
    }
}
