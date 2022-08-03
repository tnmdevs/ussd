<?php

namespace TNM\USSD\Http;

use TNM\USSD\Screen;

abstract class XMLResponse implements UssdResponseInterface
{
    protected Screen $screen;

    abstract protected function getPayload(): array;

    public function respond(Screen $screen): string
    {
        $this->screen = $screen;

        $response = file_get_contents(__DIR__ . '/response.xml');

        foreach ($this->getPayload() as $placeholder => $value)
            $response = str_replace(sprintf('{{%s}}', $placeholder), $value, $response);

        return $response;
    }
}
