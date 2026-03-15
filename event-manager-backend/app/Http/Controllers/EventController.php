<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventDescriptionRequest;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller {

    public function __construct(
        private EventService $eventService
    ) {
    }

    public function index(Request $request) {
        return response()->json([
            'data' => $this->eventService->listForUser($request->user()),
        ]);
    }

    public function store(StoreEventRequest $request) {
        $event = $this->eventService->createForUser(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'data' => $event,
        ], 201);
    }

    public function update(UpdateEventDescriptionRequest $request, Event $event) {
        $this->authorize('update', $event);

        $event = $this->eventService->updateDescription(
            $event,
            $request->validated()['description'] ?? null
        );

        return response()->json([
            'data' => $event,
        ]);
    }

    public function destroy(Request $request, Event $event) {
        $this->authorize('delete', $event);

        $this->eventService->delete($event);

        return response()->noContent();
    }
}
