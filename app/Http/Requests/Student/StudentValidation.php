<?php

namespace App\Http\Requests\Student;

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
            'firstName'=>'required|string|min:2|max:50',
            'lastName'=>'required|string|min:2|max:50',
            'nationalId'=>'required|digits:10',
            'birthday'=>'nullable|date',
            'isOnlyChild'=>'nullable|boolean',
            'address'=>'nullable|string|min:2|max:100',
            'classroom_id' => 'required|exists:classrooms,id',
            'phone'=>'required|digits:11',
            'socialMediaID'=>'nullable|string|min:1|max:50',
            'numberOfGlasses'=>'nullable|digits|max:10',
            'isLeftHand'=>'nullable|boolean',
            'religion'=>'nullable|string|min:2|max:50',
            'specialDisease'=>'nullable|string|min:2|max:50',

        ];
    }
}
