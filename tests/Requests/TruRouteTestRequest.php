<?php

namespace TNM\USSD\Test\Requests;

use JetBrains\PhpStorm\Pure;
use TNM\Utils\Factories\KeyFactory;

class TruRouteTestRequest extends XMLRequest
{
    private string $msisdn;
    private int $type;
    private string $session;
    private string $message;

    #[Pure]
    public function __construct(string $message, bool $initial = true, string $msisdn = null, string $session = null)
    {
        $this->msisdn = $msisdn ?? sprintf('26588%s', (new KeyFactory(7, true))->make());
        $this->type = $initial ? 1 : 2;
        $this->session = $session ?? (new KeyFactory(10, true))->make();
        $this->message = $message;
    }

    function getTemplate(): string
    {
        return file_get_contents(__DIR__ . '/tru.route.request.xml');
    }

    function getPayload(): array
    {
        return [
            'msisdn' => $this->msisdn,
            'session_id' => $this->session,
            'type' => $this->type,
            'message' => $this->message,
        ];
    }
}
