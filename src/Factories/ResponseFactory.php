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
        switch (request()->route('adapter')) {
            case 'flares':
                return resolve(FlaresResponse::class);
            default:
                return resolve(TruRouteResponse::class);
        }
    }
}
