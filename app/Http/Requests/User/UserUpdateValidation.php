<?php

namespace App\Http\Requests\User;

use App\Traits\MessageTrait;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateValidation extends FormRequest
{
    use MessageTrait;
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
            'name'=>'nullable|string|min:1|max:50',
            'firstName'=>'nullable|string|min:1|max:50',
            'lastName'=>'nullable|string|min:1|max:50',
            'nationalId'=>'nullable|digits:10',
            'degree'=>'nullable|string|min:1|max:50',
            'personalId'=>'nullable|string',
            'phone'=>'nullable|digits:11|unique:users,phone,'.auth()->user()->id,
            'isAssistant'=>'nullable|boolean',
        ];
    }
}
