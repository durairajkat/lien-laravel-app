<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/admins-edit.php';

// Page setup
$page = new WscPage('admins-edit');
$page->setTitle($login->admins() ? 'Edit An Admin' : 'View An Admin');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');
$page->setStyleSheet('css/admins.css');
$page->setJavaScript('js/admins.js');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php $page->putTemplate('footer'); ?>