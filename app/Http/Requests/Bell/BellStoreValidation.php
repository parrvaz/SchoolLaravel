<?php

namespace App\Http\Requests\Bell;

use Illuminate\Foundation\Http\FormRequest;

class BellStoreValidation extends FormRequest
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
            'order'=>'required|numeric|min:1|max:10',
            'startTime'=>'required|date_format:h:i',
            'endTime'=>'nullable|date_format:h:i',
        ];
    }
}
