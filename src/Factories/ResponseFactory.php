<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\Flares\FlaresResponse;
use TNM\USSD\Http\TruRoute\TruRouteResponse;
use TNM\USSD\Http\UssdResponseInterface;
use function request;

class ResponseFactory
{
    public function make(): UssdResponseInterface
    {
        return match (request()->route('adapter')) {
            'flares' => resolve(FlaresResponse::class),
            default => resolve(TruRouteResponse::class),
        };
    }
}
