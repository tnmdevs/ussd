<?php


namespace TNM\USSD\Screens;


use TNM\USSD\Http\Request;
use TNM\USSD\Http\Response;
use TNM\USSD\Screen;

class Error extends Screen
{
    public function __construct(Request $request, string $message = '')
    {
        parent::__construct($request);
        $this->message = $message;
    }

    private $message;

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return $this->message;
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return [];
    }

    /**
    * Previous screen
    * return Screen $screen
    */
    public function previous(): Screen
    {
        return new Welcome($this->request);
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute()
    {
        // TODO: Implement execute() method.
    }

    public function acceptsResponse(): bool
    {
        return false;
    }
}
