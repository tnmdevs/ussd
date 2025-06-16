<?php

namespace TNM\USSD\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractSession extends Model
{
    protected $guarded = [];

    public static function findBySessionId(string $session): ?Session
    {
        return static::where('session_id', $session)->first();
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

    public function payload()
    {
        return $this->hasMany(Payload::class);
    }

    public function mark(string $state)
    {
        $this->update(['state' => $state]);
        return $this;
    }

    public function addPayload(string $key, $value)
    {
        $value = is_array($value) ? json_encode($value) : $value;
        $this->payload()->create(['key' => $key, 'value' => $value]);
    }

    public function getPayload(string $key)
    {
        return $this->payload()->where('key', $key)->latest()->first()->{'value'};
    }

    public function getPayloads(): Collection
    {
        return $this->payload()->get();
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
