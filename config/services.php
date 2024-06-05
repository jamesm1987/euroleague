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
    'api-football' => [
        'season' =>  env('API_FOOTBALL_SEASON', 2023),
        'key' =>  env('API_FOOTBALL_API_KEY', ''),
        'base_uri' => env('API_FOOTBALL_BASE_URI', ''),
        'host' => env('API_FOOTBALL_HOST', ''),
        'team_logo_url' => env('API_FOOTBALL_TEAM_LOGO_URL', ''),
        'league_logo_url' => env('API_FOOTBALL_LEAGUE_LOGO_URL', ''),
        'country_logo_url' => env('API_FOOTBALL_COUNTRY_LOGO_URL', ''),
    ],


];
