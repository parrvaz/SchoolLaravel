<?php

namespace App\Http\Requests\Student;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class StudentValidation extends FormRequest
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
            'firstName'=>'required|string|min:1|max:50',
            'lastName'=>'required|string|min:1|max:50',
            'nationalId'=>'required|digits:10',
            'classroom_id' => 'required|exists:classrooms,id',
            'phone'=>'required|digits:11|unique:students|unique:users',
            'fatherPhone'=>'required|digits:11|unique:students,fatherPhone|unique:users,phone',
            'motherPhone'=>'nullable|digits:11',
            'birthday'=>['nullable', new JalaliDateValidation()],
            'address'=>'nullable|string|min:1|max:100',

            'isOnlyChild'=>'nullable|boolean',
            'socialMediaID'=>'nullable|string|min:1|max:50',
            'numberOfGlasses'=>'nullable|digits|max:10',
            'isLeftHand'=>'nullable|boolean',
            'religion'=>'nullable|string|min:1|max:50',
            'specialDisease'=>'nullable|string|min:1|max:50',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

        ];
    }
}
