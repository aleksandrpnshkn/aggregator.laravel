<?php

namespace Tests\Unit\Helpers;

use App\Helpers\TelegramUsername;
use PHPUnit\Framework\TestCase;

class TelegramUsernameTest extends TestCase
{
    public function testValid()
    {
        $rawUsernames = [
            '@aleksandrkrzhn',
            '@test123',
            'withoutAt',
            'with_underscore',
        ];

        foreach ($rawUsernames as $rawUsername) {
            $this->assertTrue(TelegramUsername::isValid($rawUsername), $rawUsername);
        }
    }

    public function testInvalid()
    {
        $rawUsernames = [
            '@aleksan drkrzhn',
            '@-test123',
            '@with@at@in@the@middle',
            '123+123',
        ];

        foreach ($rawUsernames as $rawUsername) {
            $this->assertFalse(TelegramUsername::isValid($rawUsername), $rawUsername);
        }
    }

    public function testWithAtSymbol()
    {
        $rawUsernames = [
            '@withAt',
            'withoutAt',
        ];

        foreach ($rawUsernames as $rawUsername) {
            $this->assertTrue(TelegramUsername::withAtSymbol($rawUsername)[0] === '@', $rawUsername);
        }
    }

    public function testWithoutAtSymbol()
    {
        $rawUsernames = [
            '@withAt',
            'withoutAt',
        ];

        foreach ($rawUsernames as $rawUsername) {
            $this->assertTrue(TelegramUsername::withAtSymbol($rawUsername)[0] === '@', $rawUsername);
        }
    }
}
