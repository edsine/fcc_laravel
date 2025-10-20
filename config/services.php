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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_REGION'), 
        'endpoint' => env('AWS_END_POINT'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
        
    ],
    'sms' => [
    'url' => env('SMS_API_URL'),            // e.g. 'https://sms-provider.example.com/api'
    'owneremail' => env('SMS_OWNER_EMAIL'),     // saleahmadu@gmail.com
    'subacct' => env('SMS_SUBACCT'),            // FEDERAL-CC
    'subacctpwd' => env('SMS_SUBACCTPWD'),      // Federal@1#
    'sender' => env('SMS_SENDER'),              // FED.CHAR.CO
    'api_key' => env('SMS_API_KEY'),  
    'sender_id' => env('SMS_SENDER_ID'),  
],

];
