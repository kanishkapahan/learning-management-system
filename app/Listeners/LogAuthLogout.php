<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Logout;

class LogAuthLogout
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function handle(Logout $event): void
    {
        if ($event->user) {
            $this->activityLogService->log('auth.logout', 'User logged out', $event->user, $event->user);
        }
    }
}
