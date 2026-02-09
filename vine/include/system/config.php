<?php

/**
 * FRAMEWORK DEPENDENCY - DO NOT MODIFY!
 * ---
 * This is the bootstrap's default config. It helps ensure that the the proper exceptions
 * get thrown if the developer's config is lacking a required setting. It does NOT
 * safeguard against invalid settings.
 */

return [
    'test-mode'       => FALSE,
    'test-ip'         => NULL,
    'global-https'    => FALSE,
    'global-nosniff'  => FALSE,
    'timezone'        => 'UTC',
    'error-reporting' => -1,
    'display-errors'  => TRUE,
    'cache-path'      => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'cache',
    'default-error'   => "We're sorry, but the system was unable to complete your request.",
    'db' => [
        'type'     => 'mysql',
        'database' => NULL,
        'host'     => 'localhost',
        'port'     => NULL,
        'socket'   => NULL,
        'user'     => NULL,
        'pass'     => NULL,
    ],
    'emails' => [
        'library-name'  => NULL,
        'library-path'  => NULL,
        'from-email'    => NULL,
        'from-name'     => NULL,
        'smtp-host'     => NULL,
        'smtp-user'     => NULL,
        'smtp-pass'     => NULL,
        'smtp-port'     => 25,
        'smtp-security' => NULL,
        'test-mode'     => FALSE,
        'test-email'    => NULL,
    ],
    'logs' => [
        'dir'        => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'logs',
        'file-dates' => 'Y/M',
        'debug'      => TRUE,
        'errors'     => TRUE,
        'events'     => TRUE,
        'exceptions' => TRUE,
    ],
    'sessions' => [
        'lifetime'    => 1200,
        'path'        => '/',
        'domain'      => NULL,
        'secure'      => FALSE,
        'httponly'    => TRUE,
        'db-enable'   => FALSE,
        'db-registry' => NULL,
        'db-table'    => NULL,
    ],
    'crypt' => [
        'key'  => 'change-me!',
        'flag' => 'make-me-different!',
        'auto' => FALSE,
    ],
];
