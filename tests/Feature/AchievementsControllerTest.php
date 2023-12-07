<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AchievementSeeder;
use Database\Seeders\BadgeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(AchievementSeeder::class);
        $this->seed(BadgeSeeder::class);
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user have not done anything
     */
    public function test_the_application_returns_the_correct_data_when_the_user_have_not_done_anything(): void
    {
        /** Setup */
        $user = User::factory()->create();

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => [],
            'next_available_achievements' => ['First Lesson Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 4,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 1 lesson
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_1_lesson(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 1);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched'],
            'next_available_achievements' => ['5 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 3,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 5 lessons
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_5_lessons(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 5);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched'],
            'next_available_achievements' => ['10 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 10 lessons
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_10_lessons(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 10);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched'],
            'next_available_achievements' => ['25 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 1,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 25 lessons
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_25_lessons(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 25);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched'],
            'next_available_achievements' => ['50 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 4,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched'],
            'next_available_achievements' => ['First Comment Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 3,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons and written 1 comment
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons_and_written_1_comment(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);
        $this->writeComments($user, 1);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written'],
            'next_available_achievements' => ['3 Comments Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 2,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons and written 3 comments
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons_and_written_3_comments(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);
        $this->writeComments($user, 3);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written', '3 Comments Written'],
            'next_available_achievements' => ['5 Comments Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 1,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons and written 5 comments
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons_and_written_5_comments(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);
        $this->writeComments($user, 5);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written'],
            'next_available_achievements' => ['10 Comments Written'],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaining_to_unlock_next_badge' => 2,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons and written 10 comments
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons_and_written_10_comments(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);
        $this->writeComments($user, 10);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
            ],
            'next_available_achievements' => ['20 Comments Written'],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaining_to_unlock_next_badge' => 1,
        ]);
    }

    /**
     * Verify that the AchievementsController returns the correct data when the user has watched 50 lessons and written 20 comments
     */
    public function test_the_application_returns_the_correct_data_when_the_user_has_watched_50_lessons_and_written_20_comments(): void
    {
        /** Setup */
        $user = User::factory()->create();
        $this->watchLessons($user, 50);
        $this->writeComments($user, 20);

        /** Act */
        $response = $this->get("/users/{$user->id}/achievements");

        /** Assert */
        $response->assertJson([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
            ],
            'next_available_achievements' => [],
            'current_badge' => 'Master',
            'next_badge' => null,
            'remaining_to_unlock_next_badge' => 0,
        ]);
    }
}
