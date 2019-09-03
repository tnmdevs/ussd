<?php


namespace TNM\USSD\Exceptions;


use App\Screens\Error;
use TNM\USSD\Http\Request;

class UssdException extends \Exception
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request, string $message)
    {
        parent::__construct($message);
        $this->request = $request;
    }

    public function render()
    {
        $error = new Error($this->request);
        $error->setMessage($this->getMessage());
        return $error->render();
    }
}
