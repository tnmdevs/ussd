<?php


namespace TNM\USSD\Http;

use \Illuminate\Http\Response as BaseResponse;


class Response extends BaseResponse
{
    const RESPONSE = 2, RELEASE = 3;
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $type;

    public function __construct(string $message, int $type)
    {
        parent::__construct();
        $this->message = $message;
        $this->type = $type;
    }
}
