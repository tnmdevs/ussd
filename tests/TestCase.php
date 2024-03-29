<?php

namespace TNM\USSD\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
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
