<?php

namespace TNM\USSD\Test\Unit;

use TNM\USSD\Test\Requests\FlaresTestRequest;
use TNM\USSD\Test\Requests\TruRouteTestRequest;
use TNM\USSD\Test\TestCase;

class ControllerTest extends TestCase
{
    public function test_throws_if_tru_route_request_is_invalid()
    {
        $content = (new FlaresTestRequest('Welcome'))->make();

        $response = $this->call('POST', 'api/ussd', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('The system could not process your request');
    }

    public function test_throws_if_flares_request_is_invalid()
    {
        $content = (new TruRouteTestRequest('Welcome'))->make();

        $response = $this->call('POST', 'api/ussd/flares', [], [], [], [], $content);


        $response->assertOk();
        $response->assertSeeText('The system could not process your request');
    }
}
