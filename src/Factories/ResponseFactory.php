<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\UssdResponse;
use TNM\USSD\Http\UssdResponseInterface;

class ResponseFactory
{
    public static function make(): UssdResponseInterface
    {
        return new UssdResponse();
    }
}
