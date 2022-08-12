<?php

namespace TNM\USSD\Test\Requests;

use JetBrains\PhpStorm\Pure;
use TNM\Utils\Factories\KeyFactory;

class FlaresTestRequest extends XMLRequest
{
    private string $msisdn;
    private string $session;
    private string $message;

    #[Pure]
    public function __construct(string $message, string $msisdn = null, string $session = null)
    {
        $this->msisdn = $msisdn ?? sprintf('26599%s', (new KeyFactory(7, true))->make());
        $this->session = $session ?? (new KeyFactory(10, true))->make();
        $this->message = $message;
    }

    function getTemplate(): string
    {
        return file_get_contents(__DIR__ . '/flares.request.xml');
    }

    function getPayload(): array
    {
        return [
            'msisdn' => $this->msisdn,
            'session_id' => $this->session,
            'message' => $this->message,
        ];
    }
}
