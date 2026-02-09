<?php

/**
 * Login an member. Member must be active.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Prepare login
$login = new Login_Member();

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
    $this->setUrl('../member/');
    $this->finish();
// Login successful
} else {
    $this->setStatus(TRUE);
    $this->setMessage('Logged in successfully!');
    $this->setUrl('main');
    $this->finish();
}