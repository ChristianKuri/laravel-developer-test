<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedListenerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }

    /**
     * A basic feature test example.
     */
    public function test_lesson_watched_dispatched(): void
    {
        /** Setup */
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        /** Fire Lesson Evement */
        Event::fake([LessonWatched::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the event was fired
        Event::assertDispatched(LessonWatched::class, function ($e) use ($lesson, $user) {
            return $e->lesson->id === $lesson->id && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement First Lesson Watched
     * and that the user does not have any other achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_lesson_watched_listener_first_lesson_watched(): void
    {
        /** Setup */
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        /** Fire Lesson Event */
        Event::fake([AchievementUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user has the achievement First Lesson Watched
        $this->assertTrue($user->achievements()->where('name', 'First Lesson Watched')->exists());

        // assert that the user does not have any other achievements
        $this->assertEquals(1, $user->achievements()->count());

        // assert that the event AchievementUnlocked is fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name === 'First Lesson Watched' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 5 Lessons Watched
     * and that the user have 2 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_lesson_watched_listener_five_lesson_watched(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 4);

        /** Fire Lesson Events */
        Event::fake([AchievementUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user has the achievement 5 Lessons Watched
        $this->assertTrue($user->achievements()->where('name', '5 Lessons Watched')->exists());

        // assert that the user have 2 achievements
        $this->assertEquals(2, $user->achievements()->count());

        // assert that the event AchievementUnlocked is fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name === '5 Lessons Watched' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 10 Lessons Watched
     * and that the user have 3 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_lesson_watched_listener_ten_lesson_watched(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 9);

        /** Fire Lesson Events */
        Event::fake([AchievementUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user has the achievement 10 Lessons Watched
        $this->assertTrue($user->achievements()->where('name', '10 Lessons Watched')->exists());

        // assert that the user have 3 achievements
        $this->assertEquals(3, $user->achievements()->count());

        // assert that the event AchievementUnlocked is fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name === '10 Lessons Watched' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 25 Lessons Watched
     * and that the user have 4 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_lesson_watched_listener_twenty_five_lesson_watched(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 24);

        /** Fire Lesson Events */
        Event::fake([AchievementUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user has the achievement 25 Lessons Watched
        $this->assertTrue($user->achievements()->where('name', '25 Lessons Watched')->exists());

        // assert that the user have 4 achievements
        $this->assertEquals(4, $user->achievements()->count());

        // assert that the event AchievementUnlocked is fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name === '25 Lessons Watched' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 50 Lessons Watched
     * and that the user have 5 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_lesson_watched_listener_fifty_lesson_watched(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $this->watchLessons($user, 49);

        /** Fire Lesson Events */
        Event::fake([AchievementUnlocked::class]);
        $user->watch($lesson);

        /** Asserts */
        // assert that the user has the achievement 50 Lessons Watched
        $this->assertTrue($user->achievements()->where('name', '50 Lessons Watched')->exists());

        // assert that the user have 5 achievements
        $this->assertEquals(5, $user->achievements()->count());

        // assert that the event AchievementUnlocked is fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name === '50 Lessons Watched' && $e->user->id === $user->id;
        });
    }
}
