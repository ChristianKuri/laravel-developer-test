<?php

namespace Tests\Feature;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenListenerTest extends TestCase
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
    public function test_comment_written_dispatched(): void
    {
        /** Setup */
        $user = User::factory()->create();

        /** Act */
        Event::fake([CommentWritten::class]);
        $user->writeComment();

        /** Assert */
        // assert that the event was fired
        Event::assertDispatched(CommentWritten::class, function ($e) use ($user) {
            return $e->comment->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement First Comment Written
     * and that the user does not have any other achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_comment_written_listener_first_comment_written(): void
    {
        /** Setup */
        $user = User::factory()->create();

        /** Fire Comment Event */
        Event::fake([AchievementUnlocked::class]);
        $user->writeComment();

        /** Asserts */
        // assert that the user has the achievement First Comment Written
        $this->assertTrue($user->hasAchievement('First Comment Written'));

        // assert that the user does not have any other achievements
        $this->assertEquals(1, $user->achievements()->count());

        // assert that the event AchievementUnlocked was fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name  === 'First Comment Written' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 3 Comments Written
     * and that the user have 2 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_comment_written_listener_3_comments_written(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->writeComments($user, 2);

        /** Fire Comment Event */
        Event::fake([AchievementUnlocked::class]);
        $user->writeComment();

        /** Asserts */
        // assert that the user has the achievement 3 Comments Written
        $this->assertTrue($user->hasAchievement('3 Comments Written'));

        // assert that the user have 2 achievements
        $this->assertEquals(2, $user->achievements()->count());

        // assert that the event AchievementUnlocked was fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name  === '3 Comments Written' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 5 Comments Written
     * and that the user have 3 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_comment_written_listener_5_comments_written(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->writeComments($user, 4);

        /** Fire Comment Event */
        Event::fake([AchievementUnlocked::class]);
        $user->writeComment();

        /** Asserts */
        // assert that the user has the achievement 5 Comments Written
        $this->assertTrue($user->hasAchievement('5 Comments Written'));

        // assert that the user have 3 achievements
        $this->assertEquals(3, $user->achievements()->count());

        // assert that the event AchievementUnlocked was fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name  === '5 Comments Written' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 10 Comments Written
     * and that the user have 4 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_comment_written_listener_10_comments_written(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->writeComments($user, 9);

        /** Fire Comment Event */
        Event::fake([AchievementUnlocked::class]);
        $user->writeComment();

        /** Asserts */
        // assert that the user has the achievement 10 Comments Written
        $this->assertTrue($user->hasAchievement('10 Comments Written'));

        // assert that the user have 4 achievements
        $this->assertEquals(4, $user->achievements()->count());

        // assert that the event AchievementUnlocked was fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name  === '10 Comments Written' && $e->user->id === $user->id;
        });
    }

    /**
     * Test that the user has the achievement 20 Comments Written
     * and that the user have 5 achievements
     * and that the event AchievementUnlocked is fired
     */
    public function test_comment_written_listener_20_comments_written(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->writeComments($user, 19);

        /** Fire Comment Event */
        Event::fake([AchievementUnlocked::class]);
        $user->writeComment();

        /** Asserts */
        // assert that the user has the achievement 20 Comments Written
        $this->assertTrue($user->hasAchievement('20 Comments Written'));

        // assert that the user have 5 achievements
        $this->assertEquals(5, $user->achievements()->count());

        // assert that the event AchievementUnlocked was fired
        Event::assertDispatched(AchievementUnlocked::class, function ($e) use ($user) {
            return $e->achievement_name  === '20 Comments Written' && $e->user->id === $user->id;
        });
    }
}
