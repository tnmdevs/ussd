<?php


namespace TNM\USSD\Http;


interface UssdRequestInterface
{
    public function getMsisdn(): ?string;

    public function getSession(): ?string;

    public function getType(): ?int;

    public function getMessage(): ?string;
}
