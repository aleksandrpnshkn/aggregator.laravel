<?php

namespace Tests\Feature\Models;

use App\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramTest extends TestCase
{
    use RefreshDatabase;

    public function testDateScopes()
    {
        self::assertEquals(0, Program::count());

        $upcoming = factory(Program::class)->create([
            'starts_at' => now()->addWeek(),
            'ends_at' => now()->addWeeks(2),
        ]);
        $current = factory(Program::class)->create([
            'starts_at' => now()->subWeek(),
            'ends_at' => now()->addWeek(),
        ]);
        $ended = factory(Program::class)->create([
            'starts_at' => now()->subWeeks(2),
            'ends_at' => now()->subWeek(),
        ]);

        self::assertEquals(1, Program::upcoming()->count());
        self::assertEquals($upcoming->id, Program::upcoming()->first()->id);

        self::assertEquals(1, Program::current()->count());
        self::assertEquals($current->id, Program::current()->first()->id);

        self::assertEquals(1, Program::ended()->count());
        self::assertEquals($ended->id, Program::ended()->first()->id);

        self::assertEquals(2, Program::notEnded()->count());
        self::assertEquals([$upcoming->id, $current->id], Program::notEnded()->get()->pluck('id')->toArray());
    }
}
