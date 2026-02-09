<?php

// Dependencies
//require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('project-dash');
$page->setTitle('Project Dashboard');
$page->setTemplate('header_blank', 'vine/member/templates/header_blank.php');
$page->setTemplate('footer', 'vine/member/templates/footer.php');
$page->setTemplate('project_dash', 'vine/member/templates/project_dash.php');

?>

<?php $page->putTemplate('header_blank'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<?php //$page->putTemplate('project_dash'); ?>
<?php
// Temporary workaround to get blade variables working
require_once "vine/member/templates/project_dash.php";
?>

<?php //$page->putTemplate('footer'); ?>