<?php

namespace App\Policies;

use App\Models\SupportChat;
use App\Models\User;

class SupportChatPolicy
{
    public function view(User $user, SupportChat $chat): bool {
        return $user->role === 'agent' || $user->id === $chat->user_id;
    }

    public function assign(User $user, SupportChat $chat): bool {
        return $user->role === 'agent';
    }

    public function sendAgentMessage(User $user, SupportChat $chat): bool {
        return $user->role === 'agent';
    }
}