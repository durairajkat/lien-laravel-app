<?php

/**
 * Delete an admin.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Secure action
$login = new Login_Admin();
$login->setAccessRules('isLoggedIn;hasFullAccess');

// Delete admin
$admin = new Model_Admin($this->db);
$admin->load('id = ?', $this->input('id'));
$admin->delete();

// Successful
$this->setStatus(TRUE);
$this->setMessage('Admin deleted successfully!');
$this->setUrl('admins-results');
$this->finish();