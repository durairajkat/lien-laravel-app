<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/admins-results.php';

// Page setup
$page = new WscPage('admins-results');
$page->setTitle($login->admins() ? 'Manage Admins' : 'List Admins');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');
$page->setStyleSheet('css/admins.css');
$page->setJavaScript('js/admins.js');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php $page->putTemplate('footer'); ?>