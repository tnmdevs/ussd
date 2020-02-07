<?php


namespace TNM\USSD;


use TNM\USSD\Http\Request;
use TNM\USSD\Http\Response;
use TNM\USSD\Screens\Welcome;

abstract class Screen
{
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
     * @return string
     */
    protected function payload(string $key): string
    {
        return $this->request->trail->getPayload($key);
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
        return Response::RESPONSE;
    }

    /**
     * Get value equivalent to the selected option
     *
     * @param int $value
     * @return string
     */
    public function getItemAt(int $value): string
    {
        if (!array_key_exists($value -1, $this->options())) return null;
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
    protected function getRequestValue(): string
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
        $this->request->trail->update(['state' => static::class]);
        return response()->ussd(
            sprintf("%s\n%s%s",
                $this->message(),
                $this->optionsAsString(),
                $this->goesBack() ? "0. Home \n#. Back": ""
            ), $this->type()
        );
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

    public function outOfRange(): bool
    {
        return !$this->withinRange();
    }

    public function doesntHaveOptions(): bool
    {
        return empty($this->options());
    }

    public function withinRange(): bool
    {
        if ($this->doesntHaveOptions()) return true;
        if ($this->request->message == '#' || $this->request->message == '0') return true;
        if (!is_numeric($this->request->message)) return false;
        return $this->request->message <= count($this->options());
    }
}
