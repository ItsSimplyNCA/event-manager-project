<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\SupportChat;
use App\Policies\EventPolicy;
use App\Policies\SupportChatPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {
        //
    }

    public function boot(): void {
        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(SupportChat::class, SupportChatPolicy::class);
    }
}