<?php

namespace App\Http\Requests\Bell;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class SetJustifiedValidation extends FormRequest
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
            'date'=>['required', new JalaliDateValidation()],
            'student_id.*'=>'required|exists:students,id',
        ];
    }
}
