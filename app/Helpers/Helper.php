<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class Helper
{
    public static function incrementSlug(string $slug, int $maxLength = 100) : string
    {
        $inc = 1;
        $hasInc = preg_match('/(-)(\d+)$/', $slug, $matches);

        if ($hasInc) {
            $inc = (int)$matches[2] + 1;
            $slug = preg_replace('/-\d+$/', '', $slug);
        }

        $incLength = strlen((string)$inc) + 1;

        return Str::limit($slug, $maxLength - $incLength, '') .  '-' . $inc;
    }
}
