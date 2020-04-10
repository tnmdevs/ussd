<?php


namespace TNM\USSD\Http;


class UssdRequest implements UssdRequestInterface
{

    public static function getProperties()
    {
        return json_decode(json_encode(simplexml_load_string(request()->getContent())), true);
    }
}
