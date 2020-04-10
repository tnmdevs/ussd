<?php

namespace TNM\USSD;

use Illuminate\Support\ServiceProvider;
use TNM\USSD\Commands\Install;
use TNM\USSD\Commands\MakeScreenFactory;
use TNM\USSD\Commands\MakeUssd;
use TNM\USSD\Http\UssdRequest;
use TNM\USSD\Http\UssdRequestInterface;
use TNM\USSD\Http\UssdResponse;
use TNM\USSD\Http\UssdResponseInterface;

class UssdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeUssd::class,
                Install::class,
                MakeScreenFactory::class
            ]);
        }

        $this->app->bind(UssdRequestInterface::class, UssdRequest::class);
        $this->app->bind(UssdResponseInterface::class, UssdResponse::class);
    }
}
