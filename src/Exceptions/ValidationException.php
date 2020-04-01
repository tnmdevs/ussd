<?php

namespace TNM\USSD\Exceptions;

use TNM\USSD\Http\Request;
use TNM\USSD\Screens\ValidationFailure;

class ValidationException extends UssdException
{
    /**
     * @var Request
     */
    protected $request;

    public function render()
    {
        return (new ValidationFailure($this->request, $this->getMessage()))->render();
    }
}
