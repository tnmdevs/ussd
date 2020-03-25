<?php


namespace TNM\USSD\Http;


use Exception;
use Illuminate\Support\Facades\Validator;
use TNM\USSD\Exceptions\UssdException;
use TNM\USSD\Exceptions\ValidationException;

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
     * @param Request $request
     * @param string|null $label
     * @return bool
     * @throws UssdException
     * @throws Exception
     */
    protected function validate(Request $request, string $label = null): bool
    {
        if (!$label) $label = 'value';
        if (empty($this->rules())) throw new Exception("You need to define rules to validate against");

        $validator = Validator::make([$label => $this->getRequestValue()], [$label => $this->rules()]);
        if ($validator->fails()) throw new ValidationException($request, $validator->errors()->first());

        return true;
    }
}
