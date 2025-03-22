<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'firstName'=>'required|string|min:1|max:50',
            'lastName'=>'required|string|min:1|max:50',
            'nationalId'=>'required|digits:10',
            'degree'=>'nullable|string|min:1|max:50',
            'personalId'=>'nullable|string',
            'phone'=>'required|digits:11|unique:teachers,phone,'.auth()->user()->id,
            'isAssistant'=>'required|boolean',

            'password'=>['required','string','min:8'],
        ];
    }
}
