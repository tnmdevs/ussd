<?php


namespace App\Screens;


use TNM\USSD\Screen;

class Error extends Screen
{
    private $message;
    /**
     * @param $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

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
        return [
            // To be replaced with an array of your options
        ];
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
}
