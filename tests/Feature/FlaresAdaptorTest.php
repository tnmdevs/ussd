<?php

namespace TNM\USSD\Test\Feature;

use TNM\USSD\Test\Requests\FlaresTestRequest;
use TNM\USSD\Test\TestCase;

class FlaresAdaptorTest extends TestCase
{
    public function test_send_request_to_flares_adapter()
    {
        $content = (new FlaresTestRequest('Welcome'))->make();

        $response = $this->call('POST', 'api/ussd/flares', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('Welcome to the USSD App');
    }
}
