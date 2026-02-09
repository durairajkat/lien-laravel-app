<?php

// Dependencies
require_once '../include/bootstrap.php';
require_once 'loaders/main.php';

// Page setup
$page = new WscPage('main');
$page->setTitle('Dashboard Main');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<?php echo Vine_Ui::getMessages(); ?>

<h1 class="h1 line-bottom">
    Welcome, Bob!
</h1>

<p>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ornare risus ac mauris
    lobortis, a ultricies velit feugiat. Morbi ornare massa at odio dictum porta. Vivamus
    dapibus vulputate est sit amet tristique. Nullam volutpat laoreet urna sed dapibus.
    Phasellus enim sapien, varius at augue id, rhoncus congue mauris. Nulla sed arcu
    vulputate, viverra lorem vel, molestie ex. Morbi elementum purus ut felis imperdiet
    lobortis. Maecenas accumsan justo et augue dictum varius.
</p>

<p>
    Pellentesque at tellus arcu. Quisque et turpis id tellus euismod varius a in nisi.
    Integer et hendrerit elit, vitae pharetra lorem. Quisque tincidunt neque sit amet
    libero hendrerit, vitae efficitur ligula bibendum. Donec mi libero, sollicitudin sed
    sem ut, aliquet cursus lacus. Ut viverra, neque venenatis dictum vulputate, sem leo
    convallis orci, quis malesuada massa massa eget odio. In nec odio rutrum, viverra nunc
    et, condimentum purus. Mauris hendrerit, ex nec finibus sagittis, massa dolor rutrum
    purus, ut vehicula velit urna quis est.
</p>

<?php $page->putTemplate('footer'); ?>