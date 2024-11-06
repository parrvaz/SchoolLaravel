<?php

namespace App\Http\Requests\Exam;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudentHomeworkStoreValidation extends FormRequest
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
            'homework_id' => 'required|exists:homework,id',
            'note' => 'nullable|string|max:240',
            'pdf' => 'required|mimes:pdf|max:5120',
        ];
    }
}
