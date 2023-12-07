<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $unlockedAchievements = $user->achievements->pluck('name');
        $nextAvailableAchievements = $this->getNextAvailableAchievements($user);
        $currentBadge = $user->badge();
        $nextBadge = $this->getNextBadge($user);
        $remainingToUnlockNextBadge = $nextBadge ? $nextBadge->threshold - $user->achievements->count() : 0;

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge ? $currentBadge->name : null,
            'next_badge' => $nextBadge ? $nextBadge->name : null,
            'remaining_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ]);
    }

    private function getNextAvailableAchievements(User $user)
    {
        $watchedCount = $user->watched()->count();

        $response = [];

        $nextLessonWatchedAchievement = Achievement::where('type', 'lessons_watched')
            ->where('threshold', '>', $watchedCount)
            ->orderBy('threshold', 'asc')
            ->first();

        if ($nextLessonWatchedAchievement) {
            $response[] = $nextLessonWatchedAchievement->name;
        }

        $nextCommentWrittenAchievement = Achievement::where('type', 'comments_written')
            ->where('threshold', '>', $user->comments->count())
            ->orderBy('threshold', 'asc')
            ->first();

        if ($nextCommentWrittenAchievement) {
            $response[] = $nextCommentWrittenAchievement->name;
        }

        return $response;
    }

    private function getNextBadge(User $user)
    {
        $totalAchievements = $user->achievements()->count();
        return Badge::where('threshold', '>', $totalAchievements)
            ->orderBy('threshold', 'asc')
            ->first();
    }
}
