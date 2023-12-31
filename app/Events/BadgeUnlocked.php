<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BadgeUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $badge_name;
    public $user;

    /**
     * Create a new event instance.
     * 
     * @param  string  $badge_name
     * @param  User  $user
     * @return void
     */
    public function __construct(string $badge_name, User $user)
    {
        $this->badge_name  = $badge_name;
        $this->user = $user;
    }
}
