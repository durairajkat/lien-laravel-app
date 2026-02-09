<?php

// Redirect to main page if already logged in
$login = new Login_Admin();
$login->setAccessRules('isNotLoggedIn', 'main', FALSE);

// Setup form
$form = new Vine_Form();
$form->setMethod('post');
$form->setAction('action?action=index');