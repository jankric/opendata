<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://localhost:5173',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:5173',
        'https://opendata.gorontalokab.go.id',
        'https://walidata.gorontalokab.go.id',
        'https://api-opendata.gorontalokab.go.id',
        'https://*.gorontalokab.go.id',
    ],

    'allowed_origins_patterns' => [
        '#^https://.*\.gorontalokab\.go\.id$#',
        '#^https://.*\.netlify\.app$#',
        '#^https://.*\.vercel\.app$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        'X-API-Version',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-RateLimit-Reset',
    ],

    'max_age' => 0,

    'supports_credentials' => true,

];