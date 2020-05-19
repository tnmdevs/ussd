<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Session extends Model
{
    protected $guarded = [];

    public static function findBySessionId(string $session): Session
    {
        return static::where('session_id', $session)->firstOrFail();
    }

    public static function findByPhoneNumber(string $phone): Collection
    {
        return static::where('msisdn', $phone)->get();
    }

    public static function notCreated(string $session): bool
    {
        return static::where('session_id', $session)->doesntExist();
    }

    public static function track(string $session, string $state, string $msisdn): self
    {
        return static::create([
            'session_id' => $session,
            'state' => $state,
            'msisdn' => $msisdn
        ]);
    }

    public function mark(string $state)
    {
        $this->update(['state' => $state]);
        return $this;
    }

    public function addPayload(string $key, $value)
    {
        if (!empty($this->{'payload'})) $payload = json_decode($this->{'payload'}, true);
        $payload[$key] = $value;
        $this->update(['payload' => json_encode($payload)]);
    }

    public function getPayload(string $key)
    {
        $payload = $this->{'payload'};
        if (empty($payload)) return null;

        $arr = json_decode($payload, true);
        if (array_key_exists($key, $arr)) return $arr[$key];

        return null;
    }

    public function setLocale(string $locale): self
    {
        app()->setLocale($locale);
        $this->update(['locale' => $locale]);
        return $this;
    }

    public function getLocale(): string
    {
        return $this->{'locale'};
    }
}
