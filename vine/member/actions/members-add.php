<?php

/**
 * Add an member.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Secure page
$login = new Login_Member();
$login->setAccessRules('isLoggedIn;hasFullAccess');

// Support data
$status = implode(',', array_keys(Options::members_status()));
$access = implode(',', array_keys(Options::members_access()));

// Required fields
$this->setRules('first_name', 'length[2,64]', 'Invalid first name.');
$this->setRules('last_name', 'length[2,64]', 'Invalid last name.');
$this->setRules('email', 'email', 'Invalid email address.');
$this->setRules('password', 'length[6,0]', 'Password must be at least 6 characters.');
$this->setRules('password', 'matches[password_again]', 'Passwords don\'t match.');
$this->setRules('status', 'inside[' . $status . ']', 'Invalid status.');
$this->setRules('access', 'inside[' . $access . ']', 'Invalid access level.');

// Query this email address
$sql = "SELECT id "
     . "FROM members "
     . "WHERE email = ? "
     . "AND deleted IS NULL "
     . "LIMIT 1";

// Email found
if ($this->db->fetch($sql, $this->input('email'))) {
    $this->setError('email', 'Another member is using the specified email address.');
}

// Errors found
if ( ! $this->isValid()) {
    $this->setStatus(FALSE);
    $this->setMessage('Please correct the following errors:');
    $this->setRedirect(FALSE, 'members-add');
    $this->finish();
}

/**
 * @date    2019-04-01
 * @author  Tell Konkle
 * ---
 * Please use password_hash() and password_verify() in production. The hash_hmac() method is for
 * PHP <= 5.6 (which Vine no longer supports anyways).
 */

// Compile salty password
$key  = Vine_Registry::getSetting('password-key');
$salt = Vine_Security::makeRandomString(32);
$pass = hash_hmac('sha512', $this->input('password') . $salt, $key);

// Compile record
$data = [
	'first_name' => $this->input('first_name'),
	'last_name'  => $this->input('last_name'),
	'email'      => $this->input('email'),
	'salt'       => $salt,
	'password'   => $pass,
	'status'     => $this->input('status'),
	'access'     => $this->input('access'),
	'modified'   => date('Y-m-d H:i:s'),
	'created'    => date('Y-m-d H:i:s'),
];

// Insert member
$this->db->insert('members', $data);

// Successful
$this->setStatus(TRUE);
$this->setMessage('Member added successfully!');
$this->setUrl('members-results');
$this->finish();
