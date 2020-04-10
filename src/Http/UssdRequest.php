<?php


namespace TNM\USSD\Http;


class UssdRequest implements UssdRequestInterface
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
        return $this->request['sessionid'];
    }

    public function getType(): int
    {
        return $this->request['type'];
    }

    public function getMessage(): string
    {
        return $this->request['msg'];
    }
}
