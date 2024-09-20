<?php

namespace App\Http\Requests\MessageBox;

use Illuminate\Foundation\Http\FormRequest;

class MessageBoxValidation extends FormRequest
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
            "message"=>"string",
            "user_id"=>"required|exists:users,id"
        ];
    }
}
