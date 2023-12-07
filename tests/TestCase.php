<?php

namespace Tests;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function watchLessons(User $user, int $count): void
    {
        $lessons = Lesson::factory()->count($count)->create();
        foreach ($lessons as $lesson) {
            $user->watch($lesson);
        }
    }

    public function writeComments(User $user, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $user->writeComment();
        }
    }
}
