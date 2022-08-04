<?php

namespace TNM\USSD\Exceptions;

use TNM\USSD\Http\Request;
use TNM\USSD\Screens\ValidationFailure;

class ValidationException extends UssdException
{
    protected Request $request;

    public function render(): string
    {
        return (new ValidationFailure($this->request, $this->getMessage()))->render();
    }
}
