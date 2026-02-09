<?php

// Secure page
$login = new Login_Member();
$login->setAccessRules('isLoggedIn');