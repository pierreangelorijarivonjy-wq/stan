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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'mvola' => [
        'client_id' => env('MVOLA_CONSUMER_KEY', 'TR81HPnzB5trfdirwyQFbxt5wz4a'),
        'client_secret' => env('MVOLA_CONSUMER_SECRET', 'xfvesclWqZkf06D18_5Pkwq7heAa'),
        'merchant_msisdn' => env('MVOLA_MERCHANT_MSISDN', '0382708373'),
        'base_url' => env('MVOLA_API_URL', 'https://pre-api.mvola.mg'),
        'auth_url' => env('MVOLA_AUTH_URL', 'https://developer.mvola.mg/oauth2/token'),
        'callback_url' => env('MVOLA_CALLBACK_URL'),
        'webhook_secret' => env('MVOLA_WEBHOOK_SECRET'),
    ],

    'orange' => [
        'client_id' => env('ORANGE_CLIENT_ID'),
        'client_secret' => env('ORANGE_CLIENT_SECRET'),
        'merchant_key' => env('ORANGE_MERCHANT_KEY'),
        'base_url' => env('ORANGE_API_URL'),
        'auth_url' => env('ORANGE_AUTH_URL'),
        'callback_url' => env('ORANGE_CALLBACK_URL'),
        'webhook_secret' => env('ORANGE_WEBHOOK_SECRET'),
    ],
];
