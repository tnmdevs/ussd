<?php


namespace TNM\USSD\Traits;


use Illuminate\Support\Arr;

trait HasBundledOptions
{
    abstract protected function payload(string $key, bool $assoc = false);

    abstract protected function value();

    private function find(string $find, string $payload, string $using = 'humanized', string $value = null)
    {
        return Arr::first(unserialize($this->payload($payload)),
            fn(array $array) => $array[$using] == $value ?: $this->value())[$find];
    }

    private function map(string $field, string $payload): array
    {
        return array_map(fn(array $array) => $array[$field], $this->payload($payload, true));
    }
}
