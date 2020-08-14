<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractTransactionTrail extends Model
{
    protected $guarded = [];

    public static function add(string $sessionId, string $message, string $response): self
    {
        return static::create([
            'session_id' => $sessionId,
            'message' => $message,
            'response' => $response
        ]);
    }

    public static function findBySession(string $session): Collection
    {
        return static::where('session_id', $session)->get();
    }
}
