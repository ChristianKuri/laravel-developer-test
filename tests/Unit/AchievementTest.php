<?php

namespace Tests\Unit;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_fillable_properties()
    {
        $achievement = new Achievement();

        $this->assertEquals(['name', 'type', 'threshold'], $achievement->getFillable());
    }
}
