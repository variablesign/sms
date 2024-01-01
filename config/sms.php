<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Gateway
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default sms gateway that should be used
    | to send sms messages. You can set any of the gateway names defined
    | below as defaults. Gateways can also be set when sending messages
    | which will override the default value here.
    |
    */

    'default' => 'smsonlinegh',

    /*
    |--------------------------------------------------------------------------
    | Gateways
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many sms gateways as you wish.
    | Each gateway has 3 endpoints, 'send', 'balance' and 'report.
    | 'send' = The endpoint for sending sms messages.
    | 'balance' = The endpoint for check available sms credits or balance.
    | 'report' = The endpoint to check if sms messages were delivered.
    | 
    | When defining custom gateways, you can choose to define only  
    | supported endpoints. Undefined endpoints have a default value of null.
    | 
    | You can also assign the following hidden options to each gateway.
    | 
    | 'timeout' => 30 
    | Sets the connection timeout in seconds for the gateway. [Default: 10 seconds]
    |
    | 'verify' => false
    | Disables SSL verification. Used for non-HTTPS endpoints. [Default: true]
    |
    */

    'gateways' => [

        'smsonlinegh' => [
            'endpoints' => [
                'send' => 'https://api.smsonlinegh.com/v4/message/sms/send',
                'balance' => 'https://api.smsonlinegh.com/v4/report/balance',
                'report' => 'https://api.smsonlinegh.com/v4/report/message/delivery',
            ],
            'key' => null,
            'sender' => null,
        ],

        'usmsgh' => [
            'endpoints' => [
                'send' => 'https://webapp.usmsgh.com/api/sms/send',
                'balance' => 'https://webapp.usmsgh.com/api/balance',
                'report' => 'https://webapp.usmsgh.com/api/sms/:id',
            ],
            'key' => null,
            'sender' => null,
        ],

        'mnotify' => [
            'endpoints' => [
                'send' => 'https://api.mnotify.com/api/sms/quick',
                'balance' => 'https://api.mnotify.com/api/balance/sms',
                'report' => 'https://api.mnotify.com/api/campaign/:id/delivered',
            ],
            'key' => null,
            'sender' => null,
        ],

        'arkesel' => [
            'endpoints' => [
                'send' => 'https://sms.arkesel.com/api/v2/sms/send',
                'balance' => 'https://sms.arkesel.com/api/v2/clients/balance-details',
                'report' => 'https://sms.arkesel.com/api/v2/sms/:id',
            ],
            'key' => null,
            'sender' => null,
            'sandbox' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Drivers
    |--------------------------------------------------------------------------
    |
    | Drivers are just Classes which handle the sms logic.
    | All drivers must extend \VariableSign\Sms\Contracts\Driver class.
    | Each gateway must have a corresponding driver class else an
    | exception will be thrown.
    |
    */

    'drivers' => [
        'smsonlinegh' => \VariableSign\Sms\Drivers\SmsOnlineGh::class,
        'usmsgh' => \VariableSign\Sms\Drivers\UsmsGh::class,
        'mnotify' => \VariableSign\Sms\Drivers\Mnotify::class,
        'arkesel' => \VariableSign\Sms\Drivers\Arkesel::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Channel Name
    |--------------------------------------------------------------------------
    |
    | The name of the notification to use in the via() method.
    | Set this option to false to disable it and use the class name instead.
    |
    */

    'channel_name' => 'sms',
];
