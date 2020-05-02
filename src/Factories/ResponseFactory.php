<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\FlaresResponse;
use TNM\USSD\Http\UssdResponse;
use TNM\USSD\Http\UssdResponseInterface;
use function request;

class ResponseFactory
{
    public function make(): UssdResponseInterface
    {
        switch (request()->route('adapter')) {
            case 'flares':
                return new FlaresResponse();
            default:
                return new UssdResponse();
        }
    }
}
