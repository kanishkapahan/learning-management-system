<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Failed;

class LogAuthFailed
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function handle(Failed $event): void
    {
        $identifier = $event->credentials['email'] ?? $event->credentials['username'] ?? 'unknown';
        $this->activityLogService->log('auth.failed', 'Failed login attempt', null, $event->user, [
            'identifier' => $identifier,
        ]);
    }
}
