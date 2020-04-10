<?php


namespace TNM\USSD;


use TNM\USSD\Http\Request;
use TNM\USSD\Http\Response;
use TNM\USSD\Http\UssdResponseInterface;
use TNM\USSD\Screens\Error;
use TNM\USSD\Screens\Welcome;

abstract class Screen
{
    const PREVIOUS = '#';
    const HOME = '0';
    /**
     * USSD Request object
     *
     * @var Request
     */
    public $request;

    /**
     * Screen constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Add message to the screen
     *
     * @return string
     */
    abstract protected function message(): string;

    /**
     * Add options to the screen
     * @return array
     */
    abstract protected function options(): array;

    /**
     * The screen to go to when BACK option is chosen
     * @return Screen
     */
    abstract public function previous(): Screen;

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    abstract protected function execute();

    /**
     * Create an instance of a screen
     *
     * @param Request $request
     * @return Screen
     */
    public static function getInstance(Request $request): Screen
    {
        if (!$request->trail->{'state'}) return new Welcome($request);
        return new $request->trail->{'state'}($request);
    }

    /**
     * Retrieve payload passed to the session
     * @param string $key
     * @param bool $assoc
     * @return string|array
     */
    protected function payload(string $key, bool $assoc = false)
    {
        $value = $this->request->trail->getPayload($key);
        return ($assoc) ? unserialize($value) : $value;
    }


    /**
     * Add payload to the session
     * @param string $key
     * @param $value
     * @param bool $assoc
     * @return void
     */
    public function addPayload(string $key, $value, bool $assoc = false)
    {
        $value = ($assoc && is_array($value)) ? serialize($value) : $value;
        return $this->request->trail->addPayload($key, $value);
    }

    /**
     * Check if the screen has payload
     * @param string $key
     * @return bool
     */
    public function hasPayload(string $key): bool
    {
        return !empty($this->payload($key));
    }

    /**
     * Response type: Release or Response
     *
     * @return int
     */
    protected function type(): int
    {
        return $this->acceptsResponse() ? Response::RESPONSE : Response::RELEASE;
    }

    protected function acceptsResponse(): bool
    {
        return true;
    }

    /**
     * Get value equivalent to the selected option
     *
     * @param $value
     * @return string
     */
    public function getItemAt($value): string
    {
        if ($this->doesntHaveOptions()) return $value;
        if (!array_key_exists($value - 1, $this->options())) return null;
        return $this->options()[$value - 1];
    }

    /**
     * Prepare the options as output string
     *
     * @return string
     */
    protected function optionsAsString(): string
    {
        $string = '';
        for ($i = 0; $i < count($this->options()); $i++) {
            $string .= sprintf("%s. %s\n", $i + 1, $this->options()[$i]);
        }
        return $string;
    }

    /**
     * Retrieve the value passed with the USSD response
     *
     * @return string
     */
    public function getRequestValue(): string
    {
        if ($this->withinRange()) return $this->getItemAt($this->request->message);

        return $this->request->message;
    }

    protected function goesBack(): bool
    {
        return $this->type() === Response::RESPONSE;
    }

    /**
     * Render the USSD response
     *
     * @return string
     */
    public function render(): string
    {
        $this->makeTrail();

        return UssdResponseInterface::make($this->getResponseMessage(), $this->type());
    }

    /**
     * Handle USSD request
     *
     * @param Request $request
     * @return mixed
     */
    public static function handle(Request $request)
    {
        return (static::getInstance($request))->execute();
    }

    public function doesntHaveOptions(): bool
    {
        return empty($this->options());
    }

    public function outOfRange(): bool
    {
        return !$this->withinRange();
    }

    public function withinRange(): bool
    {
        if ($this->doesntHaveOptions() || $this->inOptions($this->request->message)) return true;

        return $this->request->message == self::PREVIOUS || $this->request->message == self::HOME;
    }

    public function inOptions(string $value): bool
    {
        return array_key_exists($value - 1, $this->options());
    }

    public function value(): string
    {
        return $this->getRequestValue();
    }

    private function nav(): string
    {
        return $this->goesBack() ? sprintf("%s. Home \n%s. Back", Screen::HOME, Screen::PREVIOUS) : "";
    }

    private function getResponseMessage(): string
    {
        return sprintf("%s\n%s%s", $this->message(), $this->optionsAsString(), $this->nav());
    }

    private function makeTrail(): void
    {
        if ($this instanceof Error) return;

        if ($this->request->trail) $this->request->trail->mark(static::class);
    }
}
