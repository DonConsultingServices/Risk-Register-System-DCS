<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Within Redis, you may define multiple databases. These databases can be
    | used to store different data types, such as cache, sessions, and more.
    | You can specify a different connection for each database.
    |
    | To use a specific database, you can either:
    |   1. Use the `Redis::select()` method to switch databases
    |   2. Use the `Redis::connection()` method to specify a connection
    |   3. Use the `Redis::command()` method to execute commands
    |
    */

    'default' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DB', 0),
    ],

    'cache' => [
        'url' => env('REDIS_URL'),
        'host' => env('REDIS_HOST', '127.0.0.1'),
        'username' => env('REDIS_USERNAME'),
        'password' => env('REDIS_PASSWORD'),
        'port' => env('REDIS_PORT', 6379),
        'database' => env('REDIS_CACHE_DB', 1),
    ],

];
