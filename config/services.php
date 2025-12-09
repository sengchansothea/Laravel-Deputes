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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    ],
    'sso' => [
        'client_id' => env('SSO_CLIENT_ID'),
        'secret_key' => env('SSO_SECRET_KEY'),
        'base_url' => env('SSO_BASE_URL', 'https://uat-accounts.mlvt.gov.kh'),
        'logout_path' => env('SSO_LOGOUT_PATH', '/logout-sso'),
        'login_cookie_api' => env('SSO_LOGIN_COOKIE_API', '/api/v1/api-request/account/account-login-by-cookie'),
    ],


];
