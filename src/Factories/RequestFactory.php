<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\UssdRequest;
use TNM\USSD\Http\UssdRequestInterface;

class RequestFactory
{
    public static function make(): UssdRequestInterface
    {
        return new UssdRequest();
    }
}
