<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default MongoDB Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the MongoDB connections below you wish
    | to use as your default connection for all MongoDB work.
    |
    */

    'default' => env('MONGODB_CONNECTION', 'mongodb'),

    /*
    |--------------------------------------------------------------------------
    | MongoDB Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the MongoDB connections setup for your application.
    |
    */

    'connections' => [

        'mongodb' => [
            'driver' => 'mongodb',
            'host' => env('MONGODB_HOST', 'mongodb'),
            'port' => env('MONGODB_PORT', 27017),
            'database' => env('MONGODB_DATABASE', 'turismo'),
            'username' => env('MONGODB_USERNAME', ''),
            'password' => env('MONGODB_PASSWORD', ''),
            'options' => [
                'appName' => env('APP_NAME', 'Laravel'),
            ],
        ],

    ],

];
