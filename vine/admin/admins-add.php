<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/admins-add.php';

// Page setup
$page = new WscPage('admins-add');
$page->setTitle('Add An Admin');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');
$page->setStyleSheet('css/admins.css');
$page->setJavaScript('js/admins.js');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php $page->putTemplate('footer'); ?>