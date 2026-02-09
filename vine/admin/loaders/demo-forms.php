<?php

// Secure page
$login = new Login_Admin();
$login->setAccessRules('isLoggedIn');

// Setup form
$form = new Vine_Form();
$form->setMethod('post');
$form->setAction('action?action=demo-form');