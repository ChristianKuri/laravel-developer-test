<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Models\Achievement;

class LessonWatchedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $user = $event->user;
        $watchedCount = $user->watched()->count();

        // Check for lesson watched achievements
        $achievement = Achievement::where('type', 'lessons_watched')
            ->where('threshold', '<=', $watchedCount)
            ->orderBy('threshold', 'desc')
            ->first();

        if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            $user->achievements()->attach($achievement->id);
            event(new AchievementUnlocked($achievement->name, $user));
        }
    }
}
