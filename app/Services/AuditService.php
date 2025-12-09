<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditService
{
    public static function logActivity($user, $action, $expired = null, $description = null)
    {
        $request = request();
        ActivityLog::create([
            'user_id'    => $user->id,
            'action'     => $action,
            'expired_at' => $expired,
            'description'=> $description,
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->userAgent(),
        ]);
    }

}
