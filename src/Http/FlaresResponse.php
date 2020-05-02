<?php


namespace TNM\USSD\Http;


use TNM\USSD\Screen;

class FlaresResponse implements UssdResponseInterface
{

    public function respond(Screen $screen)
    {
        $content = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $content .= sprintf("<response><msisdn>%s</msisdn>", $screen->request->msisdn);
        $content .= sprintf("<applicationResponse>%s</applicationResponse></response>", $screen->getResponseMessage());
        return $content;
    }
}
