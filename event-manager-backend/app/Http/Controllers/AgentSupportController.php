<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendSupportMessageRequest;
use App\Models\SupportChat;
use App\Services\ChatService;
use Illuminate\Http\Request;

class AgentSupportController extends Controller {
    public function __construct(
        private ChatService $chatService
    ) {
    }

    public function index() {
        return response()->json([
            'data' => $this->chatService->listForAgent(),
        ]);
    }

    public function assign(Request $request, SupportChat $chat) {
        $this->authorize('assign', $chat);

        $chat = $this->chatService->assignToAgent($chat, $request->user());

        return response()->json([
            'data' => $chat,
        ]);
    }

    public function sendMessage(SendSupportMessageRequest $request, SupportChat $chat) {
        $this->authorize('sendAgentMessage', $chat);

        $chat = $this->chatService->sendAgentMessage(
            $chat,
            $request->user(),
            $request->validated()['message']
        );

        return response()->json([
            'data' => $chat,
        ]);
    }
}