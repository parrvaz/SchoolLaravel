<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginValidation extends FormRequest
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
//            'phone'=>'required|numeric|digits:11|exists:users,phone',
//            'password' => ['required','string','min:6'],

            'phone' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value === 'demo') {
                        return true;
                    }

                    if (!preg_match('/^\d{11}$/', $value)) {
                        return $fail('شماره تلفن باید ۱۱ رقم عددی باشد.');
                    }

                },
            ],
            'password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value === 'demo') {
                        return true; // مجاز است
                    }

                    if (strlen($value) < 6) {
                        return $fail('رمز عبور باید حداقل ۶ کاراکتر باشد.');
                    }
                },
            ],
        ];
    }
}
