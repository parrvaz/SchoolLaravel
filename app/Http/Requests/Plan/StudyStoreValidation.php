<?php

namespace App\Http\Requests\Plan;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudyStoreValidation extends FormRequest
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
            'date' => ['required', new JalaliDateValidation()],
            'time' => "required|numeric|min:1",
            'course_id'=>'required|exists:courses,id',
        ];
    }
}
