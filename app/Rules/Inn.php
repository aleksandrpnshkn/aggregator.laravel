<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Inn implements Rule
{
    const INN_REGEXP = '/(^\d{10}$)|(^\d{12}$)/';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (bool)preg_match(self::INN_REGEXP, $value, $matches);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ИНН имеет некорректный формат.';
    }
}
