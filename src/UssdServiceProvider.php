<?php

namespace TNM\USSD;

use Illuminate\Support\Facades\Route;
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
use TNM\USSD\Models\SessionNumber;
use TNM\USSD\Models\TransactionTrail;
use TNM\USSD\Observers\PayloadObserver;
use TNM\USSD\Observers\SessionNumberObserver;
use TNM\USSD\Observers\SessionObserver;
use TNM\USSD\Observers\TransactionTrailObserver;

class UssdServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $migrations = collect([
            'create_sessions_table.php',
            'create_transaction_trails_table.php',
            'create_payloads_table.php',
            'create_historical_sessions_table.php',
            'create_historical_payloads_table.php',
            'create_historical_transaction_trails_table.php',
            'create_session_numbers_table.php',
            'create_historical_session_numbers_table.php',
        ])->map(fn(string $migration) => [
            __DIR__ . sprintf('/database/migrations/%s', $migration)
            => database_path(sprintf('migrations/%s_%s', date('Y_m_d_His', time()), $migration)),
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes($migrations->toArray(), 'migrations');
        }
        $this->registerRoutes();
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/translations', 'ussd');

        $this->publishes([__DIR__ . '/translations' => resource_path('lang/vendor/ussd'),]);

        $this->publishes([__DIR__ . '/config/ussd.php' => config_path('ussd.php'),]);

        Payload::observe(PayloadObserver::class);
        TransactionTrail::observe(TransactionTrailObserver::class);
        Session::observe(SessionObserver::class);
        SessionNumber::observe(SessionNumberObserver::class);
    }

    protected
    function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        });
    }

    protected
    function routeConfiguration(): array
    {
        return [
            'prefix' => config('ussd.routing.prefix'),
            'middleware' => config('ussd.routing.middleware'),
        ];
    }

    public
    function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/ussd.php', 'ussd'
        );

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
