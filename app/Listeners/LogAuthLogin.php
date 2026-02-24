<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;

class LogAuthLogin
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function handle(Login $event): void
    {
        $event->user->forceFill(['last_login_at' => now()])->save();
        $this->activityLogService->log('auth.login', 'User logged in', $event->user, $event->user, [
            'remember' => $event->remember,
        ]);
    }
}
