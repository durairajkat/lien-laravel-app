<?php

// Secure page
$login = new Login_Admin();
$login->setAccessRules('isLoggedIn');