<?php

// Dependencies
require_once '../../include/bootstrap.php';

/**
 * @date    2019-04-01
 * @author  Tell Konkle
 * ---
 * Please use password_hash() and password_verify() in production. The hash_hmac() method is for
 * PHP <= 5.6 (which Vine no longer supports anyways).
 */

// Password to hash
$password = 'testing';

// Setup
$salt = Vine_Security::makeRandomString(32);
$key  = Vine_Registry::getSetting('password-key');
$hash = hash_hmac('sha512', $password . $salt, $key);

// Result
echo "<pre>";
echo "<b>Salt:</b> " . $salt . "\n";
echo "<b>Hash:</b> " . $hash;
echo "</pre>";
