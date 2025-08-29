<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public function logActivity(string $activity)
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => $activity,
        ]);
    }
}