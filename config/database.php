<?php

return [
    'default' => env('DB_CONNECTION', 'pgsql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'aws-1-us-east-1.pooler.supabase.com'),
            'port' => env('DB_PORT', 6543),
            'database' => env('DB_DATABASE', 'postgres'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'prefix' => '',
            'sslmode' => 'require',
        ],
        'supabase' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', 'aws-1-us-east-1.pooler.supabase.com'),
            'port' => env('DB_PORT', 6543),
            'database' => env('DB_DATABASE', 'postgres'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'prefix' => '',
            'sslmode' => 'require',
        ],
    ],
    'migrations' => 'migrations',
];
