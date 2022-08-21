<?php

namespace TNM\USSD\Test\Feature;

use TNM\USSD\Test\Requests\TruRouteTestRequest;
use TNM\USSD\Test\TestCase;

class TruRouteAdapterTest extends TestCase
{

    public function test_send_request_to_tru_route_adapter()
    {
        $content = (new TruRouteTestRequest('Welcome'))->make();

        $response = $this->call(method: 'POST', uri: 'api/ussd/main', content: $content);

        $response->assertOk();
        $response->assertSeeText('Welcome to the USSD App');
    }
}
