<?php

return [
    'google' => ['client_id'=>env('GOOGLE_CLIENT_ID'),'client_secret'=>env('GOOGLE_CLIENT_SECRET'),'redirect'=>env('GOOGLE_REDIRECT_URI')],
    'apple'  => ['client_id'=>env('APPLE_CLIENT_ID'),'team_id'=>env('APPLE_TEAM_ID'),'key_id'=>env('APPLE_KEY_ID'),'redirect'=>env('APPLE_REDIRECT_URI')],

    // Social (ONLY the ones we actually use)
    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', env('APP_URL').'/auth/google/callback'),
    ],
    'microsoft' => [
        'client_id'     => env('MS_CLIENT_ID'),
        'client_secret' => env('MS_CLIENT_SECRET'),
        'redirect'      => env('MS_REDIRECT_URI', env('APP_URL').'/auth/microsoft/callback'),
    ],
    'facebook' => [
        'client_id'     => env('FB_CLIENT_ID'),
        'client_secret' => env('FB_CLIENT_SECRET'),
        'redirect'      => env('FB_REDIRECT_URI', env('APP_URL').'/auth/facebook/callback'),
    ],
    'uaepass' => [
        'client_id'     => env('UAEPASS_CLIENT_ID'),
        'client_secret' => env('UAEPASS_CLIENT_SECRET'),
        'redirect'      => env('UAEPASS_REDIRECT_URI', env('APP_URL').'/auth/uaepass/callback'),
        'authorize_url' => env('UAEPASS_AUTHORIZE_URL'),
        'token_url'     => env('UAEPASS_TOKEN_URL'),
        'userinfo_url'  => env('UAEPASS_USERINFO_URL'),
    ],

    // Mail / misc (unchanged)
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend' => [
        'key' => env('RESEND_KEY'),
    ],
    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
];
