<?php

namespace Tests\Unit\Rules;

use App\Rules\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    private Money $rule;

    public function setUp(): void
    {
        parent::setUp();

        $this->rule = new Money();
    }

    public function testValid()
    {
        $values = [
            '0',
            '1',
            '0.1',
            '0,1',
            '0.01',
            '11,01',
        ];

        foreach ($values as $value) {
            $this->assertTrue($this->rule->passes('m', $value), $value);
        }
    }

    public function testNegative()
    {
        $values = [
            '-1',
            '-0.1',
        ];

        foreach ($values as $value) {
            $this->assertFalse($this->rule->passes('m', $value));
        }
    }

    public function testWrongFormat()
    {
        $values = [
            '.0',
            ',00',
            '1.002',
        ];

        foreach ($values as $value) {
            $this->assertFalse($this->rule->passes('m', $value));
        }
    }

    public function testNotNumeric()
    {
        $values = [
            'qwerty',
            '1,2s',
            'r.3',
        ];

        foreach ($values as $value) {
            $this->assertFalse($this->rule->passes('m', $value));
        }
    }
}
