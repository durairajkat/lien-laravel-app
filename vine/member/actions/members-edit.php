<?php

/**
 * Edit an member.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Secure page
$login = new Login_Member();
$login->setAccessRules('isLoggedIn');

// Level 1 members can only edit their own profile
if ( ! $login->hasFullAccess() && $this->input('id') != $login->get('id')) {
    $this->setError('Access denied.');
}

// Support data
$status = implode(',', array_keys(Options::members_status()));
$access = implode(',', array_keys(Options::members_access()));

// Required fields
$this->setRules('first_name', 'length[2,64]', 'Invalid first name.');
$this->setRules('last_name', 'length[2,64]', 'Invalid last name.');
$this->setRules('email', 'email', 'Invalid email address.');
$this->setRules('status', 'inside[' . $status . ']', 'Invalid status.');

// Only applicable for full members
if ($login->hasFullAccess()) {
    $this->setRules('access', 'inside[' . $access . ']', 'Invalid access level.');
}

// Change password
if ($this->input('password')) {
    $this->setRules('password', 'length[6,0]', 'Password must be at least 6 characters');
    $this->setRules('password', 'matches[password_again]', 'Passwords don\'t match.');
}

// Query this email address
$sql = "SELECT id "
     . "FROM members "
     . "WHERE email = ? "
     . "AND id != ? "
     . "AND deleted IS NULL "
     . "LIMIT 1";

// Email found
if ($this->db->fetch($sql, $this->input('email'), $this->input('id'))) {
    $this->setError('email', 'Another member has the same email address.');
}

// Errors found
if ( ! $this->isValid()) {
    $this->setStatus(FALSE);
    $this->setMessage('Please correct the following errors:');
    $this->setUrl('members-edit');
    $this->finish();
}

// Compile record
$data = [
	'first_name' => $this->input('first_name'),
	'last_name'  => $this->input('last_name'),
	'email'      => $this->input('email'),
	'status'     => $this->input('status'),
	'access'     => $login->hasFullAccess() ? $this->input('access') : 1,
	'modified'   => date('Y-m-d H:i:s'),
];

/**
 * @date    2019-04-01
 * @author  Tell Konkle
 * ---
 * Please use password_hash() and password_verify() in production. The hash_hmac() method is for
 * PHP <= 5.6 (which Vine no longer supports anyways).
 */

// Change password
if ($this->input('password')) {
    // Compile salty password
    $key  = Vine_Registry::getSetting('password-key');
    $salt = Vine_Security::makeRandomString(32);
    $pass = hash_hmac('sha512', $this->input('password') . $salt, $key);

    // Add password to record
    $data['salt']     = $salt;
    $data['password'] = $pass;
}

// Update record
$this->db->update('members', $data, 'id = ?', 1, $this->input('id'));

// Successful (level 1 member)
if ( ! $login->hasFullAccess()) {
    $this->setStatus(TRUE);
    $this->setMessage('Profile successfully updated!');
    $this->setUrl('main');
    $this->finish();
// Successful (level 2 member)
} else {
    $this->setStatus(TRUE);
    $this->setMessage('Member edited successfully!');
    $this->setUrl('members-results');
    $this->finish();
}
