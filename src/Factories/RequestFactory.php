<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\Flares\FlaresRequest;
use TNM\USSD\Http\TruRoute\TruRouteRequest;
use TNM\USSD\Http\UssdRequestInterface;

class RequestFactory
{
    public function make(): UssdRequestInterface
    {
        return match (request()->route('adapter')) {
            'flares' => resolve(FlaresRequest::class),
            default => resolve(TruRouteRequest::class),
        };
    }
}
