<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterListRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // تبدیل JSON به آرایه یا شیء و اعتبارسنجی هر آیتم
        foreach (json_decode($value) ?? [] as $item) {
            $item = get_object_vars($item); // تبدیل شیء به آرایه
            if (!($item['searchStr'] ?? 0) || !($item['fieldName'] ?? 0)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "fieldName and searchStr is required";
    }
}
