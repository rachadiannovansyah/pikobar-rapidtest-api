<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NikRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
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
        $allowedPrefix = [
            '11', '12', '13', '14', '15', '16', '17', '18', '19', '21',
            '31', '32', '33', '34', '35', '36', '51', '52', '53', '61',
            '62', '63', '64', '65', '71', '72', '73', '74', '75', '76',
            '81', '82', '91', '92',
        ];

        $prefix = substr($value, 0, 2);

        if (in_array($prefix, $allowedPrefix) && preg_match('/^[1-9]{1}[0-9]{11}(?!0{4})[0-9]{4}$/', $value)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.nik');
    }
}
