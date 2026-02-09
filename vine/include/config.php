<?php

/**
 * @author  Tell Konkle
 * @date    2019-04-01 2:00 PM
 */

foreach (file(__DIR__ . '/../../.env') as $line) {
    if (trim($line)) {
        putenv(trim($line));
    }
}

$config = [
    'test-mode'       => TRUE,
    'test-ip'         => ['97.95.134.95'],
    'timezone'        => 'UTC',
    'error-reporting' => -1,
    'display-errors'  => TRUE,
    'base-url'        => 'http://www.nlbapp.com/',
    'cache-path'      => INC_PATH . 'cache',
    'login-key'       => 'a-unique-key',
    'password-key'    => 'another-unique-key', // Please use password_hash() and password_verify() in production. These keys are for PHP <= 5.6 (which Vine no longer supports anyways)
    'db' => [
        'type'     => getenv('VINE_DB_CONNECTION'),
        'database' => getenv('VINE_DB_DATABASE'),
        'host'     => getenv('VINE_DB_HOST'),
        'port'     => getenv('VINE_DB_PORT'),
        'socket'   => NULL,
        'user'     => getenv('VINE_DB_USERNAME'),
        'pass'     => getenv('VINE_DB_PASSWORD'),
    ],
    'emails' => [
        'library-name'  => 'SwiftMailer',
        'library-path'  => INC_PATH . 'libraries/SwiftMailer/lib/swift_required.php',
        'from-email'    => 'noreply@domain.com',
        'from-name'     => 'domain.com',
        'smtp-host'     => NULL,
        'smtp-user'     => NULL,
        'smtp-pass'     => NULL,
        'smtp-port'     => 587,
        'smtp-security' => NULL,
        'test-mode'     => TRUE,
        'test-email'    => 'wscwebtest@yahoo.com',
    ],
    'logs' => [
        'dir'        => INC_PATH . 'logs',
        'file-dates' => 'Y', // Y = include/logs/foo.log, Y/m = include/logs/2019/04/foo.log, Y/m/d = include/logs/2019/04/01/foo.log, etc.
        'debug'      => TRUE,
        'errors'     => TRUE,
        'events'     => TRUE,
        'exceptions' => TRUE,
    ],
    'sessions' => [
        'lifetime'    => 900,
        'path'        => '/',
        'domain'      => $_SERVER['SERVER_NAME'],
        'secure'      => FALSE,
        'httponly'    => TRUE,
        'db-enable'   => TRUE,
        'db-registry' => 'db',
        'db-table'    => 'sessions',
    ],
    'crypt' => [
        'key'  => 'change-me!',
        'flag' => 'make-me-different!',
        'auto' => FALSE,
    ],
];

// Developer's localhost environment
if (FALSE === strpos(__FILE__, '/www.nlbapp.com/')) {
    $config = Vine_Array::extend(TRUE, $config, [
        'test-mode'       => TRUE,
        'test-ip'         => ['127.0.0.1', '97.95.134.95'],
        'error-reporting' => -1,
        'display-errors'  => TRUE,
        'base-url'        => 'http://vine.webservicescorporation.loc', // Docker vhost
        'db' => [
            'type'     => getenv('VINE_DB_CONNECTION'),
            'database' => getenv('VINE_DB_DATABASE'),
            'host'     => getenv('VINE_DB_HOST'),
            'port'     => getenv('VINE_DB_PORT'),
            'socket'   => NULL,
            'user'     => getenv('VINE_DB_USERNAME'),
            'pass'     => getenv('VINE_DB_PASSWORD'),
        ],
        'emails' => [
            'test-mode'  => TRUE,
            'test-email' => 'tell.konkle@subscriptionsonly.com',
        ],
    ]);
}


// (array) Configuration for applicable environment
return $config;
