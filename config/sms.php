<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Provider
    |--------------------------------------------------------------------------
    |
    | Fournisseur SMS à utiliser: 'nexah', 'twilio', ou 'log' (test)
    |
    */

    'provider' => env('SMS_PROVIDER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    */

    'api_key' => env('SMS_API_KEY'),
    'api_url' => env('SMS_API_URL', 'https://api.nexah.mg/sms/send'),
    'sender_id' => env('SMS_SENDER_ID', 'EduPass'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Configuration (si provider = twilio)
    |--------------------------------------------------------------------------
    */

    'twilio_sid' => env('TWILIO_SID'),
    'twilio_token' => env('TWILIO_TOKEN'),
    'twilio_from' => env('TWILIO_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Retry & Timeout
    |--------------------------------------------------------------------------
    */

    'retry_times' => env('SMS_RETRY_TIMES', 3),
    'timeout' => env('SMS_TIMEOUT', 10), // secondes

];
