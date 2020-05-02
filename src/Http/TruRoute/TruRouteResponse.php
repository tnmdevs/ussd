<?php


namespace TNM\USSD\Http\TruRoute;


use TNM\USSD\Http\UssdResponseInterface;
use TNM\USSD\Screen;

class TruRouteResponse implements UssdResponseInterface
{
    public function respond(Screen $screen)
    {
        return sprintf(
            "<ussd><type>%s</type><msg>%s</msg><premium><cost>0</cost><ref>NULL</ref></premium></ussd>",
            $screen->type(), $screen->getResponseMessage()
        );
    }
}
