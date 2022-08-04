<?php


namespace TNM\USSD\Screens;


use TNM\USSD\Screen;

class Welcome extends Screen
{

    /**
     * Add message to the screen
     *
     * @return string
     */
    protected function message(): string
    {
        return config('ussd.default.welcome');
    }

    /**
     * Add options to the screen
     * @return array
     */
    protected function options(): array
    {
        return config('ussd.default.options');
    }

    /**
     * Execute the selected option/action
     *
     * @return mixed
     */
    protected function execute(): mixed
    {
        // TODO: Implement execute() method.
    }

    public function previous(): Screen
    {
        // TODO: Implement previous() method.
    }
}
