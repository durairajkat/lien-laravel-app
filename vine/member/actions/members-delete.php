<?php

/**
 * Delete an member.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-08
 */

// Secure action
$login = new Login_Member();
$login->setAccessRules('isLoggedIn;hasFullAccess');

// Delete member
$member = new Model_Member($this->db);
$member->load('id = ?', $this->input('id'));
$member->delete();

// Successful
$this->setStatus(TRUE);
$this->setMessage('Member deleted successfully!');
$this->setUrl('members-results');
$this->finish();