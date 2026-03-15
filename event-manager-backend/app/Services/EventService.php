<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class EventService {

    public function listForUser(User $user): Collection {
        return $user->events()
            ->orderBy('occurs_at')
            ->get();
    }

    public function createForUser(User $user, array $data): Event {
        return $user->events()->create($data);
    }

    public function updateDescription(Event $event, ?string $description): Event {
        $event->update([
            'description' => $description,
        ]);

        return $event->fresh();
    }

    public function delete(Event $event): void {
        $event->delete();
    }
}