<?php


namespace TNM\USSD\Traits;


use Illuminate\Support\Arr;

trait HasBundledOptions
{
    abstract protected function payload(string $key, bool $assoc = false);

    abstract protected function value();

    private function find(string $find, string $payload, string $using = 'humanized', string $value = null)
    {
        return Arr::first(unserialize($this->payload($payload)), function (array $array) use ($using, $value) {
            $value = $value ? $value : $this->value();
            return $array[$using] == $value;
        })[$find];
    }

    private function map(string $field, string $payload)
    {
        return array_map(function (array $array) use ($field){
            return $array[$field];
        }, $this->payload($payload, true));
    }
}
