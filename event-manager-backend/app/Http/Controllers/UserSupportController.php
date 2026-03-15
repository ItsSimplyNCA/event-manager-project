<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendSupportMessageRequest;
use App\Services\ChatService;
use Illuminate\Http\Request;

class UserSupportController extends Controller {
    
    public function __construct(
        private ChatService $chatService
    ) {
    }

    public function showCurrent(Request $request) {
        $chat = $this->chatService->getOrCreateCurrentChat($request->user());

        return response()->json([
            'data' => $chat,
        ]);
    }

    public function sendMessages(SendSupportMessageRequest $request) {
        $chat = $this->chatService->sendUserMessage(
            $request->user(),
            $request->validated()['message']
        );

        return response()->json([
            'data' => $chat,
        ]);
    }
}
