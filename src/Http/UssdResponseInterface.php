<?php


namespace TNM\USSD\Http;


interface UssdResponseInterface
{
    public function respond($message, $type);
}
