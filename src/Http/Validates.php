<?php


namespace TNM\USSD\Http;


use Illuminate\Support\Facades\Validator;
use TNM\USSD\Exceptions\UssdException;

trait Validates
{
    /**
     * Define validation rules
     *
     * @return string
     */
    abstract protected function rules(): string;

    abstract protected function getRequestValue();

    /**
     * Validate request data against given rules
     *
     * @return bool
     * @throws UssdException
     * @throws \Exception
     */
    protected function validate(): bool
    {
        if (empty($this->rules())) throw new \Exception("You need to define rules to validate against");

        $validator = Validator::make(['value' => $this->getRequestValue()], ['value' => $this->rules()]);
        if ($validator->fails()) throw new UssdException($validator->errors()->first());

        return true;
    }
}
