<?php

return [
    'client_id' => env('BOG_CLIENT_ID'),
    'secret_key' => env('BOG_SECRET_KEY'),
    'base_url' => env('BOG_BASE_URL'),
    'callback_url' => env('APP_URL').env('BOG_CALLBACK_URL'),
    'redirect_urls' => [
        'success' => env('APP_URL').env('BOG_REDIRECT_SUCCESS'),
        'fail' => env('APP_URL').env('BOG_REDIRECT_FAIL'),
    ],
    'public_key' => env('BOG_PUBLIC_KEY'),
];
