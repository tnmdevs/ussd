<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionTrail extends Model
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
}
