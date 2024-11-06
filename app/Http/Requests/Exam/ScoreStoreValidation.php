<?php

namespace App\Http\Requests\Exam;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class ScoreStoreValidation extends FormRequest
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
            'score'=>'required|numeric|min:0|max:100',
        ];
    }
}
