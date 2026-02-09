<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/members-edit.php';

// Page setup
$page = new WscPage('members-edit');
$page->setTitle($login->members() ? 'Edit An Member' : 'View An Member');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');
$page->setStyleSheet('css/members.css');
$page->setJavaScript('js/members.js');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php $page->putTemplate('footer'); ?>