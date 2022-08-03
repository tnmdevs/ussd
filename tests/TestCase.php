<?php

namespace TNM\USSD\Test;

use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use TNM\USSD\UssdServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function getPackageProviders($app): array
    {
        return [
            UssdServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        Config::set('database.default', 'testing');
    }
}
