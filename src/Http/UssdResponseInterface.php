<?php


namespace TNM\USSD\Http;


interface UssdResponseInterface
{
    public static function make($message, $type);
}
