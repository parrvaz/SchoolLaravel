<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class TeacherValidation extends FormRequest
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
            'degree'=>'nullable|string|min:1|max:50',
            'personalId'=>'nullable|string',
            'phone'=>'required|digits:11',
            'isAssistant'=>'required|boolean'
        ];
    }

    public function messages(): array

    {

        return [

            'nationalId.unique' => 'اطلاعات دبیر با این شماره ملی توسط موسسه دیگری ثبت شده است. شما میتوانید از قسمت اضافه کردن دبیر ایشان را به لیست خود اضافه کنید',

            'phone.unique' =>  'اطلاعات کاربر با این شماره تلفن توسط موسسه دیگری ثبت شده است. شما میتوانید از قسمت اضافه کردن دبیر ایشان را به لیست خود اضافه کنید',

        ];

    }
}
