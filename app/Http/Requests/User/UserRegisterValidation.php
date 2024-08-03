<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterValidation extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'mobile'=>'required|numeric|digits:11',
            'email' => 'required|string|email|max:150',
            'password' => ['required','string','min:6','regex:/^(?=.*[A-Z])(?=.*[!@#$&*_-])(?=.*[0-9])(?=.*[a-z]).{8,}$/','confirmed'],
            'req_co_net'=>'nullable|numeric|min:0|max:1',
        ];
    }
}
