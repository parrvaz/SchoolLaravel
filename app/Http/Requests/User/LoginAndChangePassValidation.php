<?php

namespace App\Http\Requests\User;

use App\Traits\MessageTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginAndChangePassValidation extends FormRequest
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
            'code' => 'required|integer|digits:4',
            'phone'=>'required|digits:11|exists:users,phone',
            'password' => ['required','string','min:8'],


            'name'=>'nullable|string|min:1|max:50',
            'schoolName'=>'nullable|string|min:1|max:100',
            'logo'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'firstName'=>'nullable|string|min:1|max:50',
            'lastName'=>'nullable|string|min:1|max:50',
            'nationalId'=>'nullable|digits:10',
            'degree'=>'nullable|string|min:1|max:50',
            'personalId'=>'nullable|string',
        ];
    }
}
