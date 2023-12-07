<?php

namespace Tests\Feature;

use App\Events\BadgeUnlocked;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BadgeUnlockedEventTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }

    /**  test the with 1 achievement the user has the Beginner badge */
    public function test_that_with_one_achievement_the_user_has_the_beginner_badge(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        /** Fire Lesson Events */
        Event::fake([BadgeUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user have 1 achievement
        $this->assertEquals(1, $user->achievements()->count());

        // assert that the user has the Beginner badge
        $this->assertEquals('Beginner', $user->badge()->name);

        // assert that the event BadgeUnlocked was fired
        Event::assertNotDispatched(BadgeUnlocked::class);
    }

    /** Test that with 2 achievements the user has the Beginner badge */
    public function test_that_with_two_achievements_the_user_has_the_beginner_badge(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 4);

        /** Fire Lesson Events */
        Event::fake([BadgeUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user have 2 achievements
        $this->assertEquals(2, $user->achievements()->count());

        // assert that the user has the Beginner badge
        $this->assertEquals('Beginner', $user->badge()->name);

        // assert that the event BadgeUnlocked was not fired
        Event::assertNotDispatched(BadgeUnlocked::class);
    }


    /** Test that with 3 achievements the user has the Beginner badge */
    public function test_that_with_three_achievements_the_user_has_the_beginner_badge(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 9);

        /** Fire Lesson Events */
        Event::fake([BadgeUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user have 3 achievements
        $this->assertEquals(3, $user->achievements()->count());

        // assert that the user has the Beginner badge
        $this->assertEquals('Beginner', $user->badge()->name);

        // assert that the event BadgeUnlocked was not fired
        Event::assertNotDispatched(BadgeUnlocked::class);
    }

    /**
     * Test that with 4 achievements the user has the Intermediate badge 
     */
    public function test_that_with_four_achievements_the_user_has_the_intermediate_badge(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 24);

        /** Fire Lesson Events */
        Event::fake([BadgeUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user have 4 achievements
        $this->assertEquals(4, $user->achievements()->count());

        // assert that the user has the Intermediate badge
        $this->assertEquals('Intermediate', $user->badge()->name);

        // assert that the event BadgeUnlocked was fired
        Event::assertDispatched(BadgeUnlocked::class, function ($e) use ($user) {
            return $e->badge_name === 'Intermediate' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that with 5 achievements the user has the Intermediate badge 
     */
    public function test_that_with_five_achievements_the_user_has_the_intermediate_badge(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 49);

        /** Fire Lesson Events */
        Event::fake([BadgeUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user have 5 achievements
        $this->assertEquals(5, $user->achievements()->count());

        // assert that the user has the Intermediate badge
        $this->assertEquals('Intermediate', $user->badge()->name);

        // assert that the event BadgeUnlocked was not fired
        Event::assertNotDispatched(BadgeUnlocked::class);
    }
}
