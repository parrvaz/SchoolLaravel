<?php

namespace App\Http\Requests\Bell;

use Illuminate\Foundation\Http\FormRequest;

class AbsentStoreValidation extends FormRequest
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
            'date'=>'required|date',
            'bell_id'=>'required|exists:bells,id',
            'classroom_id'=>'required|exists:classrooms,id',

            'students'=>'nullable|array',
            'students.*'=>'required|exists:students,id',
        ];
    }
}
