<?php

namespace Tests\Feature;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Listeners\LessonWatchedListener;
use App\Models\Lesson;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedListenerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_lesson_watched_dispatched(): void
    {
        /** Setup */
        Event::fake();
        $this->seed(AchievementSeeder::class);
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        /** Fire Lesson Evement */
        $user->watch($lesson);

        /** Asserts */
        // assert that the event was fired
        Event::assertDispatched(LessonWatched::class, function ($e) use ($lesson, $user) {
            return $e->lesson->id === $lesson->id && $e->user->id === $user->id;
        });

        // assert that the event is listened to by the LessonWatchedListener
        Event::assertListening(
            LessonWatched::class,
            LessonWatchedListener::class,
        );
    }

    // Write a test to verify that the lessonWachedListener works as expected when the event is fired.
    public function test_lesson_watched_listener_first_lesson_watched(): void
    {
        /** Setup */
        Event::fake([AchievementUnlocked::class]);
        $this->seed(AchievementSeeder::class);
        $lesson = Lesson::factory()->create();
        $user = User::factory()->create();

        /** Fire Lesson Event */
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
}
