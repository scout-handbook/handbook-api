<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'mysql' => [
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_czech_ci',
            'database' => env('DB_DATABASE'),
            'driver' => 'mysql',
            'engine' => null,
            'host' => env('DB_HOST', 'localhost'),
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'password' => env('DB_PASSWORD', ''),
            'port' => env('DB_PORT', '3306'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'unix_socket' => env('DB_SOCKET', ''),
            'username' => env('DB_USERNAME'),
        ],
        'sqlite' => [
            'database' => env('DB_DATABASE') === ':memory:'
                ? ':memory:'
                : database_path((string) env('DB_DATABASE', 'database.sqlite')),
            'driver' => 'sqlite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_DRIVER', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',
];
