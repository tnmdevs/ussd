<?php


namespace TNM\USSD\Http;


use TNM\USSD\Screen;

interface UssdResponseInterface
{
    public function respond(Screen $screen);
}
