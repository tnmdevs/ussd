<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractPayload extends Model
{
    protected $guarded = [];


    public static function findBySession(string $session): Collection
    {
        return static::where('session_id', $session)->get();
    }
}
