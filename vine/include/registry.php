<?php

/**
 * Application Registry
 * ---
 * The registry is used for applying globally accessible settings, configuration arrays,
 * and objects.
 */

// Configuration arrays and scalar settings
Vine_Registry::setSetting('base-url',     rtrim($config['base-url'], '/') . '/');
Vine_Registry::setSetting('login-key',    $config['login-key']);
Vine_Registry::setSetting('password-key', $config['password-key']);

// Set all users to see dates in eastern timezone
Vine_Session::setUserZone(Vine_Date::getZoneOffset('America/New_York', 'UTC'));
