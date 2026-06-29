<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],
    
    'PAGINATE_ROW' => env('PAGINATE_ROW', 10),
    'DASHBOARD_LIMIT'=> env('DASHBOARD_LIMIT', 5 ),
    'ADMIN_ROLE' => 'ADMIN',
    'USER_ROLE' => 'USER',
    'FRONTEND_URL' => 'https://clubs.benchmarkit.my',	
    'USER_PASSWORD' => 'Test@123',
    'CMS_PAGE_NAME' => ([['ABOUT_US' => 'About Us'], ['EVENTS' => 'Events'], ['NEWS' => 'News'], 
        ['GALLERY' => 'Gallery'], ['CONTACT' => 'Contact'], ['JOIN_CLUB' => 'Join Club'], 
        ['JOIN_CLUB_SUBMIT' => 'Join Club Submit']]),
    'JOIN_CLUB' => ['button_name' => 'JOIN BHARAT CLUB', 'content' => "<p>Having issues? Contact us directly:</p><p>CLUB PHONE: +6 019 533 1794</p><p>CLUB EMAIL: clubbharat@gmail.com</p>"]

];
