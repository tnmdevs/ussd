<?php


namespace TNM\USSD\Http;

use \Illuminate\Http\Request as BaseRequest;
use TNM\USSD\Models\Session;

class Request extends BaseRequest
{
    const INITIAL = 1, RESPONSE = 2;
    public $msisdn;
    public $session;
    public $type;
    /**
     * @var string $message
     */
    public $message;
    /**
     * @var Session
     */
    public $trail;

    public function __construct()
    {
        parent::__construct();
        $this->setProperties(request()->getContent());
        $this->setSessionLocale();
        $this->trail = $this->getTrail();
    }

    private function setProperties(string $params): void
    {
        $request = json_decode(json_encode(simplexml_load_string($params)), true);

        $this->msisdn = $request["msisdn"];
        $this->session = $request["sessionid"];
        $this->type = $request["type"];
        $this->message = $request["msg"];
    }

    private function setSessionLocale(): void
    {
        if (Session::where(['session_id' => $this->session])->doesntExist()) return;

        $session = Session::findBySessionId($this->session);
        app()->setLocale($session->{'locale'});
    }

    public function isInitial(): bool
    {
        return $this->type == self::INITIAL;
    }

    public function isResponse(): bool
    {
        return $this->type == self::RESPONSE;
    }

    private function getTrail(): Session
    {
        return Session::firstOrCreate(
            ['session_id' => $this->session],
            ['state' => 'init', 'msisdn' => $this->msisdn]
        );
    }

}
