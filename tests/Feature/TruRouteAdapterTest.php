<?php

namespace TNM\USSD\Test\Feature;

use TNM\USSD\Test\TestCase;

class TruRouteAdapterTest extends TestCase
{

    public function test_send_request_to_tru_route_adapter()
    {
        $this->artisan('make:ussd', ['name' => 'Welcome']);
        $content = file_get_contents(__DIR__ . '/tru.route.request.xml');

        $response = $this->call('POST', 'api/ussd', [], [], [], [], $content);

        $response->assertOk();
    }
}
