<?php

namespace TNM\USSD\Test\Feature;

use TNM\USSD\Test\FlaresRequest;
use TNM\USSD\Test\TestCase;

class FlaresAdaptorTest extends TestCase
{
    public function test_send_request_to_flares_adapter()
    {
        $content = (new FlaresRequest('Welcome'))->render();

        $response = $this->call('POST', 'api/ussd/flares', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('Welcome to the USSD App');
    }

    public function test_send_request_to_tru_route_adapter()
    {
        $content = (new FlaresRequest('Welcome'))->render();

        $response = $this->call('POST', 'api/ussd/flares', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('Welcome to the USSD App');
    }
}
