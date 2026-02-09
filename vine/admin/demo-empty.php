<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-empty');
$page->setTitle('Empty Page Demo');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php $page->putTemplate('footer'); ?>