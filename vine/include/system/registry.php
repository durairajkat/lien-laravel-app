<?php

/**
 * FRAMEWORK DEPENDENCY - DO NOT MODIFY!
 * ---
 * This is the Vine's default registry. These are the minimum components needed for the
 * Vine to run correctly.
 */

// Settings
Vine_Registry::setSetting(Vine::TEST_MODE, $config[Vine::TEST_MODE]);
Vine_Registry::setSetting(Vine::TEST_IP, $config[Vine::TEST_IP]);
Vine_Registry::setSetting(Vine::GLOBAL_HTTPS, $config[Vine::GLOBAL_HTTPS]);
Vine_Registry::setSetting(Vine::GLOBAL_NOSNIFF, $config[Vine::GLOBAL_NOSNIFF]);
Vine_Registry::setSetting(Vine::TIMEZONE, $config[Vine::TIMEZONE]);
Vine_Registry::setSetting(Vine::DISPLAY_ERRORS, $config[Vine::DISPLAY_ERRORS]);
Vine_Registry::setSetting(Vine::CACHE_PATH, Vine_File::strToDir($config[Vine::CACHE_PATH]));
Vine_Registry::setSetting(Vine::DEFAULT_ERROR, $config[Vine::DEFAULT_ERROR]);

// Configs
Vine_Registry::setConfig(Vine::CONFIG_EMAILS, $config[Vine::CONFIG_EMAILS]);
Vine_Registry::setConfig(Vine::CONFIG_LOGS, $config[Vine::CONFIG_LOGS]);
Vine_Registry::setConfig(Vine::CONFIG_SESSIONS, $config[Vine::CONFIG_SESSIONS]);
Vine_Registry::setConfig(Vine::CONFIG_CRYPT, $config[Vine::CONFIG_CRYPT]);

// Objects
Vine_Registry::setObject(
    Vine::CONFIG_DB,
    new Vine_Db($config[Vine::CONFIG_DB], $config[Vine::CONFIG_CRYPT])
);

// Singletons
Vine_Session::getInstance();
