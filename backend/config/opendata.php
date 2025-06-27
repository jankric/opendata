<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Portal Information
    |--------------------------------------------------------------------------
    */
    'portal' => [
        'name' => env('PORTAL_NAME', 'Portal Data Terbuka'),
        'description' => env('PORTAL_DESCRIPTION', 'Portal resmi untuk mengakses data publik'),
        'contact' => [
            'email' => env('PORTAL_CONTACT_EMAIL', 'info@example.com'),
            'phone' => env('PORTAL_CONTACT_PHONE', ''),
            'address' => env('PORTAL_ADDRESS', ''),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Settings
    |--------------------------------------------------------------------------
    */
    'uploads' => [
        'max_size' => env('MAX_UPLOAD_SIZE', 52428800), // 50MB in bytes
        'allowed_types' => explode(',', env('ALLOWED_FILE_TYPES', 'csv,json,xlsx,xls,pdf,xml,geojson')),
        'disk' => env('FILESYSTEM_DISK', 'local'),
        'path' => 'datasets',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Settings
    |--------------------------------------------------------------------------
    */
    'api' => [
        'rate_limit' => env('API_RATE_LIMIT', 100),
        'rate_limit_window' => env('API_RATE_LIMIT_WINDOW', 1), // minutes
        'version' => 'v1',
        'prefix' => 'api/v1',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dataset Settings
    |--------------------------------------------------------------------------
    */
    'datasets' => [
        'auto_approve' => env('DATASET_AUTO_APPROVE', false),
        'require_approval' => env('DATASET_REQUIRE_APPROVAL', true),
        'default_license' => 'CC-BY-4.0',
        'preview_limit' => 100, // rows for CSV preview
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Settings
    |--------------------------------------------------------------------------
    */
    'search' => [
        'min_query_length' => 3,
        'max_results' => 1000,
        'default_limit' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'ttl' => [
            'stats' => 3600, // 1 hour
            'categories' => 86400, // 24 hours
            'organizations' => 86400, // 24 hours
            'datasets' => 1800, // 30 minutes
        ],
    ],
];