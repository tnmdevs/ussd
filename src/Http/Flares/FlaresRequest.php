<?php


namespace TNM\USSD\Http\Flares;


use TNM\USSD\Http\Request;
use TNM\USSD\Http\UssdRequestInterface;
use TNM\USSD\Models\Session;

class FlaresRequest implements UssdRequestInterface
{
    /**
     * @var array
     */
    private $request;

    public function __construct()
    {
        $this->request = json_decode(json_encode(simplexml_load_string(request()->getContent())), true);
    }

    public function getMsisdn(): string
    {
        return $this->request['msisdn'];
    }

    public function getSession(): string
    {
        return $this->request['sessionId'];
    }

    public function getType(): int
    {
        return Session::findBySessionId($this->getSession())->exists() ? Request::RESPONSE : Request::INITIAL;
    }

    public function getMessage(): string
    {
        return $this->request['subscriberInput'];
    }
}
