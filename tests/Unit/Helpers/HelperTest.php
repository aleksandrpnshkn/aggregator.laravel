<?php

namespace Tests\Unit\Helpers;

use App\Helpers\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testIncrementSlug()
    {
        $this->assertEquals('test-1', Helper::incrementSlug('test'));
        $this->assertEquals('test-2', Helper::incrementSlug('test-1'));
        $this->assertEquals('test3-1', Helper::incrementSlug('test3'));
        $this->assertEquals('test3-2', Helper::incrementSlug('test3-1'));
        $this->assertEquals('test-34', Helper::incrementSlug('test-33'));
        $this->assertEquals('te-23s-23-t-24', Helper::incrementSlug('te-23s-23-t-23'));

        // Тест лимита
        $this->assertEquals('test-1', Helper::incrementSlug('test', 6));
        $this->assertEquals('tes-10', Helper::incrementSlug('test-9', 6));
    }
}
