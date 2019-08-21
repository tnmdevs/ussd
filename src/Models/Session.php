<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $guarded = [];

    public static function findBySessionId(string $session): Session
    {
        return static::where('session_id', $session)->firstOrFail();
    }

    public static function track(string $session, string $state, string $msisdn): self
    {
        return static::create([
            'session_id' => $session,
            'state' => $state,
            'msisdn' => $msisdn
        ]);
    }
}
