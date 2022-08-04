<?php

namespace TNM\USSD\Test;

use JetBrains\PhpStorm\Pure;
use TNM\Utils\Factories\KeyFactory;

class FlaresRequest
{
    private string $msisdn;
    private int $type;
    private string $session;
    private string $message;

    #[Pure]
    public function __construct(string $message, string $msisdn = null, string $session = null)
    {
        $this->msisdn = $msisdn ?? sprintf('26588%s', (new KeyFactory(7, true))->make());
        $this->session = $session ?? (new KeyFactory(10, true))->make();
        $this->message = $message;
    }

    public function render(): string
    {
        $content = file_get_contents(__DIR__ . '/flares.request.xml');
        $payload = [
            'msisdn' => $this->msisdn,
            'session_id' => $this->session,
            'message' => $this->message,
        ];

        foreach ($payload as $placeholder => $value)
            $content = str_replace(sprintf('{{%s}}', $placeholder), $value, $content);

        return $content;
    }

}
