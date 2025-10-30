<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for optimizing system performance
    | including caching, database queries, and frontend optimizations.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'dashboard_stats' => [
            'duration' => env('DASHBOARD_CACHE_DURATION', 300), // 5 minutes
            'enabled' => env('DASHBOARD_CACHE_ENABLED', true),
        ],
        'risk_matrix' => [
            'duration' => env('RISK_MATRIX_CACHE_DURATION', 300), // 5 minutes
            'enabled' => env('RISK_MATRIX_CACHE_ENABLED', true),
        ],
        'client_stats' => [
            'duration' => env('CLIENT_STATS_CACHE_DURATION', 600), // 10 minutes
            'enabled' => env('CLIENT_STATS_CACHE_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Optimization
    |--------------------------------------------------------------------------
    */
    'database' => [
        'query_timeout' => env('DB_QUERY_TIMEOUT', 30), // seconds
        'slow_query_threshold' => env('DB_SLOW_QUERY_THRESHOLD', 0.5), // seconds
        'enable_query_logging' => env('DB_ENABLE_QUERY_LOGGING', false),
        'connection_pool_size' => env('DB_CONNECTION_POOL_SIZE', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Frontend Performance
    |--------------------------------------------------------------------------
    */
    'frontend' => [
        'auto_refresh_interval' => env('FRONTEND_AUTO_REFRESH', 300000), // 5 minutes in milliseconds
        'lazy_loading_enabled' => env('FRONTEND_LAZY_LOADING', true),
        'image_optimization' => env('FRONTEND_IMAGE_OPTIMIZATION', true),
        'minify_assets' => env('FRONTEND_MINIFY_ASSETS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring & Analytics
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'performance_tracking' => env('PERFORMANCE_TRACKING', true),
        'memory_usage_threshold' => env('MEMORY_USAGE_THRESHOLD', 50), // MB
        'cache_efficiency_tracking' => env('CACHE_EFFICIENCY_TRACKING', true),
        'slow_query_logging' => env('SLOW_QUERY_LOGGING', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Performance
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limiting' => [
            'enabled' => env('API_RATE_LIMITING', true),
            'requests_per_minute' => env('API_RATE_LIMIT', 60),
        ],
        'response_caching' => [
            'enabled' => env('API_RESPONSE_CACHING', true),
            'default_ttl' => env('API_CACHE_TTL', 300), // 5 minutes
        ],
        'compression' => [
            'enabled' => env('API_COMPRESSION', true),
            'gzip_level' => env('API_GZIP_LEVEL', 6),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Optimization
    |--------------------------------------------------------------------------
    */
    'search' => [
        'fulltext_search' => [
            'enabled' => env('FULLTEXT_SEARCH_ENABLED', true),
            'min_word_length' => env('FULLTEXT_MIN_WORD_LENGTH', 3),
        ],
        'elasticsearch' => [
            'enabled' => env('ELASTICSEARCH_ENABLED', false),
            'host' => env('ELASTICSEARCH_HOST', 'localhost'),
            'port' => env('ELASTICSEARCH_PORT', 9200),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Optimization
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'image_compression' => [
            'enabled' => env('IMAGE_COMPRESSION_ENABLED', true),
            'quality' => env('IMAGE_COMPRESSION_QUALITY', 85),
            'max_dimensions' => [
                'width' => env('IMAGE_MAX_WIDTH', 1920),
                'height' => env('IMAGE_MAX_HEIGHT', 1080),
            ],
        ],
        'file_validation' => [
            'max_size' => env('FILE_MAX_SIZE', 10240), // 10MB in KB
            'allowed_types' => env('FILE_ALLOWED_TYPES', 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session & Security
    |--------------------------------------------------------------------------
    */
    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120), // minutes
        'secure_cookies' => env('SESSION_SECURE_COOKIES', false),
        'http_only' => env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue & Background Jobs
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'default' => env('QUEUE_CONNECTION', 'sync'),
        'failed_job_retry' => env('FAILED_JOB_RETRY', 3),
        'job_timeout' => env('JOB_TIMEOUT', 300), // seconds
        'batch_size' => env('QUEUE_BATCH_SIZE', 100),
    ],
];
