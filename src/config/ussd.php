<?php

return [
    'session' => [
        'last_activity_minutes' => 2,
    ],
    'routing' => [
        'prefix' => 'api/ussd',
        'middleware' => ['api'],
        'landing_screen' => \TNM\USSD\Screens\Welcome::class
    ],
    'navigation' => [
        'home' => '*',
        'previous' => '#'
    ]
];
