<?php

namespace TNM\USSD\Test\Feature;

use TNM\USSD\Test\TestCase;
use TNM\USSD\Test\TruRouteRequest;

class TruRouteAdapterTest extends TestCase
{

    public function test_send_request_to_tru_route_adapter()
    {
        $content = (new TruRouteRequest('Welcome'))->render();

        $response = $this->call('POST', 'api/ussd/main', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('Welcome to the USSD App');
    }
}
