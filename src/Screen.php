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
    private static function getInstance(Request $request): Screen
    {
        if (!$request->trail->{'state'}) return new Welcome($request);
        return new $request->trail->{'state'}($request);
    }

    /**
     * Retrieve payload passed to the session
     * @return string
     */
    protected function payload(): string
    {
        return $this->request->trail->{'payload'};
    }

    /**
     * Check if the screen has payload
     * @return bool
     */
    public function hasPayload(): bool
    {
        return $this->payload() && $this->payload() != '';
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
        if (count($this->options()) && count($this->options()) >= $this->request->message) {
            return $this->getItemAt($this->request->message);
        }
        return $this->request->message;
    }

    protected function goesBack(): bool
    {
        return true;
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
        $screen = static::getInstance($request);
        return $screen->execute();
    }

    public function outOfRange(): bool
    {
        if ($this->getRequestValue() == '#' || $this->getRequestValue() == '0') return false;
        return count($this->options()) && $this->getRequestValue() > count($this->options());
    }
}
