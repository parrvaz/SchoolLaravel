<?php

namespace App\Http\Requests\Report;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class AnalysisValidation extends FormRequest
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
        ];
    }
}
