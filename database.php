<?php

use Illuminate\Support\Str;

return [
//..
    'connections' => [

        'mysql2' => [ 
            'driver' => 'sqlsrv',
            'host' => 'HOST',
            'port' => '1433',
            'database' => 'NAME',
            'username' => 'USR',
            'password' => 'PAS',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                PDO::ATTR_PERSISTENT => true,
            ]) : [PDO::ATTR_PERSISTENT => true,],
        ],   
//..
    ],

   ];
