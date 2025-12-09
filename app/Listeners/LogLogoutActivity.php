<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class LogLogoutActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        if ($event->user) {
            Cache::forget("audit_logged_{$event->user->id}");
            Cache::forget("sso_authenticated_{$event->user->id}");
            AuditService::logActivity($event->user,'logout', null, 'User logged out');

        }
    }
}
