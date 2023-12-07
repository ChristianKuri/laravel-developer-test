<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Badge;

class AchievementUnlockedListener
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
    public function handle(AchievementUnlocked $event): void
    {
        $user = $event->user;

        // Check for badge achievements
        $totalAchievements = $user->achievements()->count();
        $badge = Badge::where('threshold', $totalAchievements)
            ->first();
        dump($totalAchievements);

        if ($badge) {
            event(new BadgeUnlocked($badge->name, $user));
        }
    }
}
