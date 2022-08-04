<?php


namespace TNM\USSD\Http\Flares;


use TNM\USSD\Http\XMLResponse;

class FlaresResponse extends XMLResponse
{
    protected function getPayload(): array
    {
        return [
            'msisdn' => $this->screen->request->msisdn,
            'message' => $this->screen->getResponseMessage(),
        ];
    }

    protected function getTemplate(): string
    {
        return __DIR__ . '/response.xml';
    }
}
