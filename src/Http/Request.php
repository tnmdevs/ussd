<?php


namespace TNM\USSD\Http;

use App\Screens\Welcome;
use Illuminate\Http\Request as BaseRequest;
use TNM\USSD\Factories\RequestFactory;
use TNM\USSD\Models\Session;
use TNM\USSD\Screen;

class Request extends BaseRequest
{
    const INITIAL = 1, RESPONSE = 2, RELEASE = 3, TIMEOUT = 4;
    public string $msisdn;
    public $session;
    public int $type;
    public string $message;
    public Session $trail;
    private UssdRequestInterface $ussdRequest;

    public function __construct()
    {
        parent::__construct();
        $this->ussdRequest = (new RequestFactory())->make();

        $this->setRequestProperties()->setSessionLocale()->setSessionTrail();
    }

    public function toPreviousScreen(): bool
    {
        return $this->message == config('ussd.navigation.previous');
    }

    public function toHomeScreen(): bool
    {
        if ($this->getExistingSession()) return false;
        return $this->isInitial() || $this->message == config('ussd.navigation.home');
    }

    public function isInvalid(): bool
    {
        return empty($this->ussdRequest->getMsisdn()) ||
            empty($this->ussdRequest->getSession()) ||
            empty($this->ussdRequest->getType()) ||
            empty($this->ussdRequest->getMessage());
    }

    private function setRequestProperties(): self
    {
        $this->msisdn = $this->ussdRequest->getMsisdn();
        $this->session = $this->ussdRequest->getSession();
        $this->type = $this->ussdRequest->getType();
        $this->message = $this->ussdRequest->getMessage();
        return $this;
    }

    private function setSessionLocale(): self
    {
        if (Session::notCreated($this->session)) return $this;

        $session = Session::findBySessionId($this->session);
        app()->setLocale($session->{'locale'});
        return $this;
    }

    public function isInitial(): bool
    {
        return $this->type == self::INITIAL;
    }

    public function isResponse(): bool
    {
        return $this->type == self::RESPONSE;
    }

    public function isTimeout(): bool
    {
        return $this->type == self::TIMEOUT;
    }

    public function isReleased(): bool
    {
        return $this->type == self::RELEASE;
    }

    public function isNotUserResponse(): bool
    {
        return $this->isInitial() || $this->isTimeout() || $this->isReleased();
    }

    public function isNotReleased(): bool
    {
        return !$this->isReleased();
    }

    public function isNotTimeout(): bool
    {
        return !$this->isTimeout();
    }

    private function getTrail(): Session
    {
        $existingSession = $this->getExistingSession();
        if ($existingSession) return $existingSession->updateSessionId($this->session);

        return Session::firstOrCreate(
            ['session_id' => $this->session],
            ['state' => Welcome::class, 'msisdn' => $this->msisdn]
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

    public function getExistingSession(): ?Session
    {
        return Session::recentSessionByPhone($this->msisdn);
    }

    private function setSessionTrail(): void
    {
        $this->trail = $this->getTrail();
    }
}
