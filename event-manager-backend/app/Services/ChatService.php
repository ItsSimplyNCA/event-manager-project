<?php

namespace App\Services;

use App\Models\SupportChat;
use App\Models\User;

class ChatService {
    public function __construct(
        private HelpDeskBotService $botService
    ) {
    }

    public function getOrCreateCurrentChat(User $user): SupportChat {
        $chat = SupportChat::query()
            ->where('user_id', $user->id)
            ->whereIn('status', ['bot', 'waiting_agent', 'agent'])
            ->latest()
            ->first();

        if ($chat) {
            return $this->loadChat($chat);
        }

        $chat = SupportChat::create([
            'user_id' => $user->id,
            'status' => 'bot',
        ]);

        return $this->loadChat($chat);
    }

    public function sendUserMessage(User $user, string $body): SupportChat {
        $chat = $this->getOrCreateCurrentChat($user);

        $chat->messages()->create([
            'sender_type' => 'user',
            'sender_user_id' => $user->id,
            'body' => $body,
        ]);

        $chat->update([
            'last_message_at' => now(),
        ]);

        if (in_array($chat->status, ['waiting_agent', 'agent'], true)) {
            return $this->loadChat($chat->fresh());
        }

        $botResult = $this->botService->respond($body);

        $chat->messages()->create([
            'sender_type' => 'bot',
            'sender_user_id' => null,
            'body' => $botResult['reply'],
        ]);

        $chat->update([
            'status' => $botResult['handoff'] ? 'waiting_agent' : 'bot',
            'last_message_at' => now(),
        ]);

        return $this->loadChat($chat->fresh());
    }

    public function listForAgent(): array {
        return SupportChat::query()
            ->with([
                'user:id,name,email',
                'assignedAgent:id,name,email',
                'messages' => fn ($query) => $query->orderBy('created_at'),
            ])
            ->whereIn('status', ['waiting_agent', 'agent'])
            ->orderByDesc('last_message_at')
            ->get()
            ->all();
    }

    public function assignToAgent(SupportChat $chat, User $agent): SupportChat {
        $chat->update([
            'assigned_agent_id' => $agent->id,
            'status' => 'agent',
        ]);

        return $this->loadChat($chat->fresh());
    }

    public function sendAgentMessage(SupportChat $chat, User $agent, string $body): SupportChat {
        if ($chat->assigned_agent_id === null) {
            $chat->update([
                'assigned_agent_id' => $agent->id,
            ]);
        }

        $chat->messages()->create([
            'sender_type' => 'agent',
            'sender_user_id' => $agent->id,
            'body' => $body,
        ]);

        $chat->update([
            'status' => 'agent',
            'last_message_at' => now(),
        ]);

        return $this->loadChat($chat->fresh());
    }

    public function loadChat(SupportChat $chat): SupportChat {
        return $chat->load([
            'user:id,name,email',
            'assignedAgent:id,name,email',
            'messages' => fn ($query) => $query->orderBy('created_at'),
        ]);
    }
}