<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'peach' => [
        'user_id' => env('PEACH_USERID'),
        'password' => env('PEACH_PASSWORD'),
        'entityId' => env('PEACH_ENTITY'),
        'recurringId' => env('PEACH_RECURRING_ID'),
        'endpoint' => env('PEACH_ENDPOINT'),
        'token' => env('PEACH_TOKEN')
    ],

    'pusher' => [
        'public' => env('PUSHER_KEY'),
        'secret' => env('PUSHER_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
    ],

    'sendinblue' => [
        'url' => 'https://api.sendinblue.com/v2.0',
        'key' => env('SENDINBLUE_KEY'),
    ],
    
    'handesk' => [
        'web_url'   => env('HANDESK_WEB_URL', 'http://localhost/handesk/public'),
        'url'   => env('HANDESK_URL', 'http://localhost/handesk/public/api'),
        'token' => env('HANDESK_TOKEN', '12345'),
        'default_password' => env('HANDESK_DEFAULT_PASSWORD', '12345'),
    ],

];
