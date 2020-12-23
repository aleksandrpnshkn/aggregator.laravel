<?php

namespace Tests\Unit\Helpers;

use App\Helpers\PhoneNumber;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testValidRaw()
    {
        $values = [
            '+78005553535',
            '+7 800 5553535',
            '+7 800 555 35 35',
            '+7 800 555-35-35',
            '+7 800 555 - 35 - 35',
            '+7(800)5553535',
            '+7 (800) 555-35-35',
            '+7 (800) 555 - 35 - 35',
            '74957556983',
            '8-903-123-45-67',
            '+71234567890',
            '89003779977',
            '72-34-15',
            '234 23 65',
        ];

        foreach ($values as $value) {
            self::assertTrue(PhoneNumber::isRawNumber($value), $value);
        }
    }

    public function testInvalidRaw()
    {
        $values = [
            'lorem',
            '+()---',
            '+(12)---',
            '7800+5553535',
            '+7 80 555 35 35 34 345 345 3453 42 234',
        ];

        foreach ($values as $value) {
            self::assertFalse(PhoneNumber::isRawNumber($value), $value);
        }
    }

    public function testValidGlobal()
    {
        $values = [
            '+78005553535',
            '+7 800 5553535',
            '+7 800 555 35 35',
            '+7 800 555-35-35',
            '+7 800 555 - 35 - 35',
            '+7(800)5553535',
            '+7 (800) 555-35-35',
            '+7 (800) 555 - 35 - 35',
            '+71234567890',
            '+ 7 800 555 35 35',
        ];

        foreach ($values as $value) {
            self::assertTrue(PhoneNumber::isGlobalNumber($value), $value);
        }
    }

    public function testInvalidGlobal()
    {
        $values = [
            '74957556983',
            '8-903-123-45-67',
            '89003779977',
            '72-34-15',
            '234 23 65',
        ];

        foreach ($values as $value) {
            self::assertFalse(PhoneNumber::isGlobalNumber($value), $value);
        }
    }

    public function testGetCleanNumber()
    {
        $cleanGlobalNumber = '+78005553535';

        $values = [
            '+78005553535',
            '+7 800 5553535',
            '+7 800 555 35 35',
            '+7 800 555-35-35',
            '+7 800 555 - 35 - 35',
            '+7(800)5553535',
            '+7 (800) 555-35-35',
            '+7 (800) 555 - 35 - 35',
        ];

        foreach ($values as $value) {
            self::assertEquals($cleanGlobalNumber, PhoneNumber::cleanNumber($value));
        }
    }
}
