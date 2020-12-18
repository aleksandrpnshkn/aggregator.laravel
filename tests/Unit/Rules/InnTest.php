<?php

namespace Tests\Unit\Rules;

use App\Rules\Inn;
use PHPUnit\Framework\TestCase;

class InnTest extends TestCase
{
    private Inn $rule;

    public function setUp(): void
    {
        parent::setUp();
        $this->rule = new Inn();
    }

    public function testValid()
    {
        $values = [
            '1234567890', // Юр лицо
            '123456789012', // Физ лицо
        ];

        foreach ($values as $value) {
            $this->assertTrue($this->rule->passes('m', $value), $value);
        }
    }

    public function testInvalid()
    {
        $values = [
            '',
            'not-numeric',
        ];

        // Проверить числа разной длины (кроме 10 и 12)
        for ($i = 1; $i < 20; $i++) {
            if ($i === 10 || $i === 12) {
                continue;
            }

            $value = str_pad('', $i, '1');
            $values[] = $value;
        }

        foreach ($values as $value) {
            $this->assertFalse($this->rule->passes('m', $value), $value);
        }
    }
}
