<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterByCodeValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'mobile'=>'required|numeric|digits:11',
            'email' => 'required|string|email|max:255',
            'password' => ['required','string','min:6','regex:/^(?=.*[A-Z])(?=.*[!@#$&*_-])(?=.*[0-9])(?=.*[a-z]).{8,}$/','confirmed'],
            'code'=>'required|integer|digits:4',
        ];
    }
}
