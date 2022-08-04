<?php

use TNM\USSD\Screens\Welcome;

return [
    'session' => [
        'last_activity_minutes' => 2,
    ],
    'routing' => [
        'prefix' => 'api/ussd',
        'middleware' => ['api'],
        'landing_screen' => Welcome::class
    ],
    'navigation' => [
        'home' => '*',
        'previous' => '#'
    ],
    'default' => [
        'options' => ['Subscribe', 'Unsubscribe', 'Help'],
        'welcome' => 'Welcome to the USSD App',
    ]
];
