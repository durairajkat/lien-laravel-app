<?php

/**
 * FRAMEWORK DEPENDENCY - DO NOT MODIFY!
 * ---
 * This is the Vine's default workspace. This file is used to apply settings and handlers
 * that the framework needs in order to operate correctly, as well as basic environment
 * settings that the developer should seldom, if ever, need to change.
 */

// Start an output buffer
ob_start();

// Display or hide errors (the exception handler below will respect this setting)
ini_set('display_errors', $config['display-errors']);

// The PHP error reporting level
error_reporting($config['error-reporting']);

// All uncaught exceptions are handled by Vine_Exception
set_exception_handler(['Vine_Exception', 'handle']);

// Call Vine_Exception::error() whenever a PHP error is found
set_error_handler(['Vine_Exception', 'error']);

// Call Vine::shutdown() when script shuts down
register_shutdown_function(['Vine', 'shutdown']);

// Default timezone (required)
date_default_timezone_set($config['timezone']);

// Use 1/sha1 for generating session ID's (PHP defaults to 0/md5)
ini_set('session.hash_function', 1);

// Number of bits per session character (160-bit sha1 / 4-bit = 40 bytes)
ini_set('session.hash_bits_per_character', 4);

// Age of session data before it's considered garbage by garbage collector
ini_set('session.gc_maxlifetime', $config['sessions']['lifetime'] * 2);

// Apply global unicode character set (hard coded into the Vine core)
ini_set('default_charset', Vine::UNICODE);
