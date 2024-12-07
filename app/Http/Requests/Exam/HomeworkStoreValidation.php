<?php

namespace App\Http\Requests\Exam;

use App\Rules\JalaliDateValidation;
use Illuminate\Foundation\Http\FormRequest;

class HomeworkStoreValidation extends FormRequest
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
            'date'=>['required', new JalaliDateValidation()],
            'course_id'=>'required|exists:courses,id',
            'expected'=>'required|numeric|min:0|max:100',
            'score'=>'required|numeric|min:0|max:100',
            'isFinal'=>'nullable|boolean',

            'title'=>'required|string',
            'description'=>'nullable|string',
            'link'=>'nullable|string',

            'classrooms'=>'required|array|min:1',
            'classrooms.*'=>'required|exists:classrooms,id',

            'voices'=>'nullable|array',
            'voices.*' => 'required|mimes:mp3,wav,ogg,webm|max:10240',

//            'photos'=>'nullable|array',
//            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
//
//            'pdfs'=>'nullable|array',
//            'pdfs.*' => 'required|mimes:pdf|max:5120',

            'files'=>'nullable|array',
            'files.*' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5120',

        ];
    }
}
