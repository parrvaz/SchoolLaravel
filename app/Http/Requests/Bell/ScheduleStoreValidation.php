<?php

namespace App\Http\Requests\Bell;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleStoreValidation extends FormRequest
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
            'classroom_id'=>'required|exists:classrooms,id',

            'list'=> 'required|array',
            'list.*.course_id'=>'required|exists:courses,id',
            'list.*.bell_id'=>'required|exists:bells,id',
            'list.*.day'=>'required|in:0,1,2,3,4,5,6',

        ];
    }
}
