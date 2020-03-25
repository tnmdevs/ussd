<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\Request;
use TNM\USSD\Screen;

abstract class ScreenFactory
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var string
     */
    private $value;

    public function __construct(Screen $screen)
    {
        $this->request = $screen->request;
        $this->value = $screen->getRequestValue();
    }

    abstract public function make();
}
