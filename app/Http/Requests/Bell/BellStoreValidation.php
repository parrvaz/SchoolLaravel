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
            'list'=> 'required|array',
            'list.*.order'=>'required|numeric|min:1|max:10',
            'list.*.startTime'=>'required|date_format:h:i',
            'list.*.endTime'=>'nullable|date_format:h:i',

        ];
    }
}
