<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Microservices Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for internal microservices integration
    |
    */

    'book_catalog' => [
        'url' => env('BOOK_CATALOG_SERVICE_URL', 'http://localhost:8001/api'),
        'token' => env('BOOK_CATALOG_SERVICE_TOKEN', 'book-catalog-service-token'),
        'timeout' => env('BOOK_CATALOG_SERVICE_TIMEOUT', 15),
    ],

    'borrowing' => [
        'url' => env('BORROWING_SERVICE_URL', 'http://localhost:8002/api'),
        'token' => env('BORROWING_SERVICE_TOKEN', 'borrowing-service-token'),
        'timeout' => env('BORROWING_SERVICE_TIMEOUT', 15),
    ],

    'review' => [
        'url' => env('REVIEW_SERVICE_URL', 'http://localhost:8003/api'),
        'token' => env('REVIEW_SERVICE_TOKEN', 'review-service-token'),
        'timeout' => env('REVIEW_SERVICE_TIMEOUT', 15),
    ],

];
