<?php


namespace TNM\USSD\Screens;


use TNM\USSD\Screen;

class ValidationFailure extends Error
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return "To be replaced with your custom message";
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
        return new $this->request->trail->{'state'}($this->request);
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

    protected function goesBack(): bool
    {
        return true;
    }
}
