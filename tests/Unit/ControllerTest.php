<?php

namespace TNM\USSD\Test\Unit;

use TNM\USSD\Test\FlaresRequest;
use TNM\USSD\Test\TestCase;
use TNM\USSD\Test\TruRouteRequest;

class ControllerTest extends TestCase
{
    public function test_throws_if_request_is_invalid()
    {
        $content = (new FlaresRequest('Welcome'))->render();

        $response = $this->call('POST', 'api/ussd', [], [], [], [], $content);

        $response->assertOk();
        $response->assertSeeText('The system could not process your request');
    }

    public function test_throws_if_flares_request_is_invalid()
    {
        $content = (new TruRouteRequest('Welcome'))->render();

        $response = $this->call('POST', 'api/ussd/flares', [], [], [], [], $content);


        $response->assertOk();
        $response->assertSeeText('The system could not process your request');
    }
}
