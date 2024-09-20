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
            'schedule'=> 'required|array|min:1',

            'schedule.*'=> 'required|array',

//            'schedule.*.course_id'=>'required|exists:courses,id',
//            'schedule.*.bell_id'=>'required|exists:bells,id',
//            'schedule.*.day'=>'required|in:0,1,2,3,4,5,6',

        ];
    }
}
