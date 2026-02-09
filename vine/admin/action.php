<?php

// Dependencies
require_once '../include/bootstrap.php';

// Action handle configuration
$config = [
    // Directory that contains actions for this action handle
    'action-path' => 'actions',

    // The default URL to redirect to for bad action requests
    'default-url' => 'main',

    // The default error message to save for bad action requests
    'default-error' => 'Request access error (code 1003).',

    // Save a full debugging log when TRUE
    'test-mode' => FALSE,

    // Save a full debugging log if IP matches (if running on localhost use 127.0.0.1)
    'test-ip' => Vine_Registry::getSetting('test-ip'),
];

// Load action handle and attempt to run the current request
$action = new Vine_Action($config, Vine_Registry::getObject('db'));
$action->prelude(INC_PATH . 'support/actions.php');
$action->run();