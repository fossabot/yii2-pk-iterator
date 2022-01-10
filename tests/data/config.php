<?php

/**
 * This is the configuration file for the Yii2 unit tests.
 * You can override configuration values by creating a `config.local.php` file
 * and manipulate the `$config` variable.
 */

$config = [
    'db' => [
        'class'    => \yii\db\Connection::class,
        'dsn'      => 'mysql:host=127.0.0.1;dbname=test',
        'username' => 'root',
        'password' => 'root',
    ],
];

if (is_file(__DIR__ . '/config.local.php')) {
    include(__DIR__ . '/config.local.php');
}

return $config;
