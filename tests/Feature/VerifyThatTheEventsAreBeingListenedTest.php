<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\AchievementUnlockedListener;
use App\Listeners\CommentWrittenListener;
use App\Listeners\LessonWatchedListener;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VerifyThatTheEventsAreBeingListenedTest extends TestCase
{
    public function test_verify_that_the_events_are_being_listened(): void
    {
        Event::fake();

        Event::assertListening(
            LessonWatched::class,
            LessonWatchedListener::class,
        );

        Event::assertListening(
            AchievementUnlocked::class,
            AchievementUnlockedListener::class,
        );

        Event::assertListening(
            CommentWritten::class,
            CommentWrittenListener::class,
        );
    }
}
