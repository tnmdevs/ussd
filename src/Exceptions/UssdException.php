<?php


namespace TNM\USSD\Exceptions;


use TNM\USSD\Http\Request;
use TNM\USSD\Screens\Error;

class UssdException extends \Exception
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request, string $message)
    {
        parent::__construct($message);
        $this->request = $request;
    }

    public function render()
    {
        return (new Error($this->request, $this->getMessage()))->render();
    }
}
