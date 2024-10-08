<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

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

    'default' => env('DB_CONNECTION', 'mysql'),

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

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '192.168.1.5'),
            'database'  => env('DB_DATABASE', 'natusi_apm'),
            'username'  => env('DB_USERNAME', 'client'),
            'password'  => env('DB_PASSWORD', 'Wahidin123'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
            // 'port' => env('DB_PORT', 3307),
			'port' => env('DB_PORT', 3306),
        ],

        'dbrsud' => [
            'driver'    => 'mysql',
            // 'host'      => env('DB_HOST_RSU', 'localhost'),
            'host'      => env('DB_HOST_RSU', '192.168.1.5:3306'),
            // 'port'      => env('DB_PORT_RSU', '8080'),
            'database'  => env('DB_DATABASE_RSU', 'forge'),
            'username'  => env('DB_USERNAME_RSU', 'forge'),
            'password'  => env('DB_PASSWORD_RSU', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'dbwahidin' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '192.168.1.5'),
            // 'host'      => env('DB_HOST', '127.0.0.1:3307'),
            // 'port'      => env('DB_PORT_RSU', '8080'),
            'database'  => 'wahidin_data2020',
            'username'  => env('DB_USERNAME_RSU', 'forge'),
            'password'  => env('DB_PASSWORD_RSU', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
            // 'port' => env('DB_PORT', 3307),
            'port' => env('DB_PORT', 3306),
        ],

        'dbrsudlain' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST_RSU', 'localhost'),
            'port'      => env('DB_PORT_RSU', '8080'),
            'database'  => 'rsu',
            'username'  => env('DB_USERNAME_RSU', 'forge'),
            'password'  => env('DB_PASSWORD_RSU', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'rsu' => [
            'driver' => 'mysql',
            //'host' => '127.0.0.1',
            'host' => '192.168.1.5',
            'port' => '3306',
            'database' => 'rsu',
            // 'username' => 'root',
            // 'password' => '',
            'username' => 'client',
            'password' => 'Wahidin123',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'dbxlink' => [
            'driver' => 'mysql',
            //'host' => '127.0.0.1',
             'host' => '192.168.1.5',
            'port' => '3306',
            'database' => 'xlink',
            // 'username' => 'root',
            // 'password' => '',
            'username' => 'client',
            'password' => 'Wahidin123',
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'local' => [
            'driver'    => 'mysql',
            'host'      => '192.168.2.134',
            'port'      => '3306',
            'database'  => 'indonesia',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'prefix'   => '',
        ],

    ],

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

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];
