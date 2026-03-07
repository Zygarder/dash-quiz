<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Updated to prioritize your .env settings while including the standard
    | local IP and Port used by Vite (5173).
    |
    */

    'stateful' => explode(',', env(
        'SANCTUM_STATEFUL_DOMAINS',
        'localhost,127.0.0.1,localhost:,127.0.0.1:5173,127.0.0.1:8000,::1'
    )),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | CRITICAL UPDATE: We added 'admin' and 'dasher' here. 
    | This tells Sanctum to look for session cookies for these specific guards.
    |
    */

    'guard' => ['web', 'admin', 'dasher'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | Ensure these point to the correct middleware classes. 
    | Note: In Laravel 11, these paths might change, but for Laravel 10 and 
    | below, these are standard.
    |
    */

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],

];