<?php

namespace App\Http\Requests\Messag;

use Illuminate\Foundation\Http\FormRequest;

class MessageValidation extends FormRequest
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
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'nullable|in:1,2',
            'recipients' => 'required|array', // شناسه‌های گیرندگان
            'recipients.*' => 'exists:users,id',
        ];
    }
}
