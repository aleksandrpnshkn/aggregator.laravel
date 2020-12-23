<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class TelegramUsername
{
    const USERNAME_REGEXP = '/^@?[A-z0-9_]{5,32}$/';

    public static function withoutAtSymbol(string $username) : ?string
    {
        if (! $username || $username[0] !== '@') {
            return $username;
        }

        return substr($username, 1);
    }

    public static function withAtSymbol(string $username) : string
    {
        return Str::startsWith($username, '@') ? $username : '@' . $username;
    }

    public static function isValid(string $username) : bool
    {
        return (bool)preg_match(self::USERNAME_REGEXP, $username);
    }
}
