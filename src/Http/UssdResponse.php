<?php


namespace TNM\USSD\Http;


class UssdResponse implements UssdResponseInterface
{

    public static function make($message, $type)
    {
        $content = sprintf(
            "<ussd><type>%s</type><msg>%s</msg><premium><cost>0</cost><ref>NULL</ref></premium></ussd>",
            $type, $message
        );
        return response()->make($content);
    }
}
