<?php

// Dependencies
require_once '../include/bootstrap.php';

// Do admin logout
if ((new Login_Admin)->doLogout()) {
    WscSession::setMessage(TRUE, 'Logged out successfully!');
}

// Redirect to login page
header('location: ' . dirname($_SERVER['PHP_SELF'])); exit;