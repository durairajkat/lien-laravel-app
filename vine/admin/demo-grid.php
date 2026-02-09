<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-grid');
$page->setTitle('Grid System Examples');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<h1 class="h1">
    Grid System Examples
</h1>

<div class="suck-right text-center">

    <div class="row flow">
        <div class="col-12 pad-right">
            <div class="zone pad-v">col-12</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-6 pad-right">
            <div class="zone pad-v">col-6</div>
        </div>
        <div class="col-6 pad-right">
            <div class="zone pad-v">col-6</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-4 pad-right">
            <div class="zone pad-v">col-4</div>
        </div>
        <div class="col-4 pad-right">
            <div class="zone pad-v">col-4</div>
        </div>
        <div class="col-4 pad-right">
            <div class="zone pad-v">col-4</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
        <div class="col-1 pad-right">
            <div class="zone pad-v">col-1</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-8 pad-right">
            <div class="zone pad-v">col-8</div>
        </div>
        <div class="col-4 pad-right">
            <div class="zone pad-v">col-4</div>
        </div>
    </div>

    <div class="row flow">
        <div class="col-2 pad-right">
            <div class="zone pad-v">col-2</div>
        </div>
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
        <div class="col-4 pad-right">
            <div class="zone pad-v">col-4</div>
        </div>
        <div class="col-3 pad-right">
            <div class="zone pad-v">col-3</div>
        </div>
    </div>

</div>

<?php $page->putTemplate('footer'); ?>