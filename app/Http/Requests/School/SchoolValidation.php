<?php

namespace App\Http\Requests\School;

use Illuminate\Foundation\Http\FormRequest;

class SchoolValidation extends FormRequest
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
            'title'=>'required|string|min:1|max:50',
            'gender'=>'nullable|bool',
            'phone'=>'nullable|string|min:1|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'nullable|string|min:1|max:50',
            'postalCode' => 'nullable|string|min:1|max:50',
            'bankAccount' => 'nullable|string|min:1|max:50',
            'website' => 'nullable|string|min:1|max:50',
            'socialMedia' => 'nullable|string|min:1|max:50',
        ];
    }
}
