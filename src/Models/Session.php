<?php

namespace TNM\USSD\Models;

class Session extends AbstractSession
{
    protected $table = 'ussd_sessions';
    
    public static function recentSessionByPhone(string $phone): ?self
    {
        return Session::where('msisdn', $phone)
            ->where('updated_at', '>=', now()->subMinutes(config('ussd.session.last_activity_minutes')))
            ->latest()->first();
    }

    public static function hasRecentSessionByPhone(string $phone): bool
    {
        return !!static::recentSessionByPhone($phone);
    }

    public function updateSessionId(string $sessionId): self
    {
        $this->update(['session_id' => $sessionId]);
        return $this;
    }
}
