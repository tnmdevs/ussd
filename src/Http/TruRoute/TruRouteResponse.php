<?php


namespace TNM\USSD\Http\TruRoute;


use TNM\USSD\Http\XMLResponse;

class TruRouteResponse extends XMLResponse
{
    protected function getPayload(): array
    {
        return [
            'type' => $this->screen->type(),
            'message' => $this->screen->getResponseMessage(),
        ];
    }
}
