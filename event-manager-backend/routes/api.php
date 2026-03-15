<?php

# use Illuminate\Http\Request;
use App\Http\Controllers\AgentSupportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserSupportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::patch('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy']);

    Route::get('/support/chat', [UserSupportController::class, 'showCurrent']);
    Route::post('/support/chat/messages', [UserSupportController::class, 'sendMessages']);

    Route::prefix('/agent')
        ->middleware('agent')
        ->group(function () {
            Route::get('/chats', [AgentSupportController::class, 'index']);
            Route::post('/chats/{chat}/assign', [AgentSupportController::class, 'assign']);
            Route::post('/chats/{chat}/messages', [AgentSupportController::class, 'sendMessage']);
        });
});
