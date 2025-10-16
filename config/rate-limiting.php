<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Here you may configure rate limiting for your application. You may
    | configure rate limiting for different types of requests and also
    | configure different rate limiting strategies for different routes.
    |
    */

    'throttle' => [
        'enabled' => env('RATE_LIMITING_ENABLED', true),
        'default' => [
            'max_attempts' => env('RATE_LIMITING_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('RATE_LIMITING_DECAY_MINUTES', 1),
        ],
        'api' => [
            'max_attempts' => env('API_RATE_LIMIT', 60),
            'decay_minutes' => 1,
        ],
        'auth' => [
            'max_attempts' => 5,
            'decay_minutes' => 15,
        ],
        'uploads' => [
            'max_attempts' => 10,
            'decay_minutes' => 60,
        ],
        'notifications' => [
            'max_attempts' => 120,
            'decay_minutes' => 1,
        ],
    ],

];
