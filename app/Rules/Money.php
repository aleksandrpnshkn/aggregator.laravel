<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Money implements Rule
{
    const MONEY_REGEXP = '/^(\d+)[.,]?(\d{0,2})$/';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $isMatches = (bool)preg_match(self::MONEY_REGEXP, $value, $matches);

        if (! $isMatches) {
            return false;
        }

        list(, $wholePart, $fractionalPart) = $matches;

        return strlen($wholePart) > 0
            && strlen($fractionalPart) >= 0
            && strlen($fractionalPart) < 3;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Поле :attribute имеет некорректный формат.';
    }
}
