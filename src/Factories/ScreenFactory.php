<?php


namespace TNM\USSD\Factories;


use TNM\USSD\Http\Request;
use TNM\USSD\Screen;

abstract class ScreenFactory
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var string
     */
    protected $value;

    public function __construct(Screen $screen)
    {
        $this->request = $screen->request;
        $this->value = $screen->getRequestValue();
    }

    abstract public function make();
}
