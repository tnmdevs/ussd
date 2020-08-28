<?php

namespace TNM\USSD\Models;

class Session extends AbstractSession
{

    public static function recentSessionByPhone(string $phone): ?self
    {
        return Session::where('msisdn', $phone)->where('created_at', '<', now()->subMinutes(2))->latest()->first();
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
