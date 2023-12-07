<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Models\Achievement;

class CommentWrittenListener
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
     */
    public function handle(CommentWritten $event): void
    {
        $user = $event->comment->user;
        $commentCount = $user->comments()->count();

        // Check for comment written achievements
        $achievement = Achievement::where('type', 'comments_written')
            ->where('threshold', '<=', $commentCount)
            ->orderBy('threshold', 'desc')
            ->first();

        if ($achievement && !$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            $user->achievements()->attach($achievement->id);
            event(new AchievementUnlocked($achievement->name, $user));
        }
    }
}
