<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\FlaresRequest;
use TNM\USSD\Http\UssdRequest;
use TNM\USSD\Http\UssdRequestInterface;

class RequestFactory
{
    public static function make(): UssdRequestInterface
    {
        switch (request()->route('adapter')) {
            case 'flares' :
                return new FlaresRequest();
            default:
                return new UssdRequest();
        }
    }
}
