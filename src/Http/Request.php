<?php


namespace TNM\USSD\Http;

use Illuminate\Http\Request as BaseRequest;
use TNM\USSD\Models\Session;
use TNM\USSD\Screen;

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

    /**
     * @var bool $valid whether the request is valid XML document or not
     */
    private $valid = false;

    public function __construct()
    {
        parent::__construct();
        $this->setProperties(UssdRequestInterface::getProperties());

        if (!$this->valid) return;

        $this->setSessionLocale();
        $this->trail = $this->getTrail();
    }

    public function toPreviousScreen(): bool
    {
        return $this->message == Screen::PREVIOUS;
    }

    public function toHomeScreen(): bool
    {
        return $this->isInitial() || $this->message == Screen::HOME;
    }

    public function invalid(): bool
    {
        return !$this->valid;
    }

    private function setValid($request): void
    {
        if (!$request) {
            $this->valid = false;
            return;
        }

        $this->valid = array_key_exists('msisdn', $request) &&
            array_key_exists('sessionid', $request) &&
            array_key_exists('type', $request) &&
            array_key_exists('msg', $request);
    }

    private function setProperties(array $request): void
    {
        $this->setValid($request);

        if ($this->valid) {
            $this->msisdn = $request["msisdn"];
            $this->session = $request["sessionid"];
            $this->type = $request["type"];
            $this->message = $request["msg"];
        }
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

    public function getScreen(): Screen
    {
        return new $this->trail->{'state'}($this);
    }

    public function getPreviousScreen(): Screen
    {
        return $this->getScreen()->previous();
    }


}
