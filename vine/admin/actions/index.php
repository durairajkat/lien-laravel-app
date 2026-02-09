<?php

/**
 * Login an admin. Admin must be active.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Prepare login
$login = new Login_Admin();

// Already logged in, stop here
if ($login->isLoggedIn()) {
    $this->setStatus(TRUE);
    $this->setUrl('main');
    $this->finish();
}

// Login credentials
$email    = $this->input('email');
$password = $this->input('password');
$remember = $this->input('remember_me') === '1' ? TRUE : FALSE;

// Attempt login
$attempt = $login->doFormLogin($email, $password, $remember);

// Login failed
if ( ! $attempt) {
    $this->setStatus(FALSE);
    $this->setMessage('Login failed. Please verify your email and password.');
    $this->setUrl('../admin/');
    $this->finish();
// Login successful
} else {
    $this->setStatus(TRUE);
    $this->setMessage('Logged in successfully!');
    $this->setUrl('main');
    $this->finish();
}