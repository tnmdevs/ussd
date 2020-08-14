<?php

namespace TNM\USSD;

use Illuminate\Support\ServiceProvider;
use TNM\USSD\Commands\AuditSession;
use TNM\USSD\Commands\CleanUp;
use TNM\USSD\Commands\Install;
use TNM\USSD\Commands\ListUserTransactions;
use TNM\USSD\Commands\MakeScreenFactory;
use TNM\USSD\Commands\MakeUssd;
use TNM\USSD\Commands\MonitorPayload;
use TNM\USSD\Commands\Update;
use TNM\USSD\Models\Payload;
use TNM\USSD\Models\Session;
use TNM\USSD\Models\TransactionTrail;
use TNM\USSD\Observers\PayloadObserver;
use TNM\USSD\Observers\SessionObserver;
use TNM\USSD\Observers\TransactionTrailObserver;

class UssdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/translations', 'ussd');

        $this->publishes([
            __DIR__ . '/translations' => resource_path('lang/vendor/ussd'),
        ]);

        Payload::observe(PayloadObserver::class);
        TransactionTrail::observe(TransactionTrailObserver::class);
        Session::observe(SessionObserver::class);
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeUssd::class,
                Install::class,
                MakeScreenFactory::class,
                CleanUp::class,
                AuditSession::class,
                ListUserTransactions::class,
                MonitorPayload::class,
                Update::class,
            ]);
        }
    }
}
