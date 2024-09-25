<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Morilog\Jalali\Jalalian;

class JalaliDateValidation implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct()
    {
        //
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
        try {
            // تلاش برای تبدیل تاریخ شمسی به میلادی برای اطمینان از صحیح بودن فرمت
            Jalalian::fromFormat('Y/m/d', $value);
            return true;
        } catch (\Exception $e) {
            // اگر خطا داشت یعنی تاریخ معتبر نیست
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'فرمت تاریخ شمسی وارد شده معتبر نیست.';
    }
}
