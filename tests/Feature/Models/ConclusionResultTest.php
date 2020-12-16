<?php

namespace Tests\Feature\Models;

use App\ConclusionResult;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConclusionResultTest extends TestCase
{
    use RefreshDatabase;

    public function testIsExpired()
    {
        /** @var ConclusionResult $conclusionResult */
        $conclusionResult = factory(ConclusionResult::class)->create([
            'ends_at' => Carbon::tomorrow(),
        ]);
        $this->assertFalse($conclusionResult->isExpired());

        $conclusionResult = factory(ConclusionResult::class)->create([
            'ends_at' => Carbon::yesterday(),
        ]);
        $this->assertTrue($conclusionResult->isExpired());

        $conclusionResult = factory(ConclusionResult::class)->create([
            'starts_at' => Carbon::yesterday(),
            'ends_at' => null,
        ]);
        $this->assertFalse($conclusionResult->isExpired());
    }
}
