<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-textual');
$page->setTitle('Textual Examples');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<h1 class="h1">The Quick Brown Fox Jumps Over The Lazy Dog...</h1>

<h2 class="h2">The Quick Brown Fox Jumps Over The Lazy Dog...</h2>

<h3 class="h3">The Quick Brown Fox Jumps Over The Lazy Dog...</h3>

<h4 class="h4">The Quick Brown Fox Jumps Over The Lazy Dog...</h4>

<h5 class="h5">The Quick Brown Fox Jumps Over The Lazy Dog...</h5>

<h6 class="h6">The Quick Brown Fox Jumps Over The Lazy Dog...</h6>

<h1 class="h1 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h1>

<h2 class="h2 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h2>

<h3 class="h3 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h3>

<h4 class="h4 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h4>

<h5 class="h5 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h5>

<h6 class="h6 line-bottom">The Quick Brown Fox Jumps Over The Lazy Dog...</h6>

<div class="alert"><b>Styleless.</b> This is an styleless alert.</div>

<div class="alert default"><b>Default.</b> This is a default alert.</div>

<div class="alert success"><b>Success!</b> This is a success alert.</div>

<div class="alert caution"><b>Caution!</b> This is a cautionary alert.</div>

<div class="alert failure"><b>Failure!</b> This is a failure alert.</div>

<p>Curabitur cursus est odio, ut aliquam quam vehicula eu. Sed ac adipiscing felis,
nec ornare leo. Donec non lobortis tellus, ut blandit lectus. Ut ornare pulvinar leo,
ut mattis massa sodales at. Donec dui justo, egestas vel pulvinar ac, convallis non
urna. Integer pellentesque lacus ut blandit gravida. Donec venenatis, tortor ac
sodales convallis, sapien urna tristique nulla, id vulputate sem enim a diam. Aenean
dictum condimentum sollicitudin. Maecenas luctus rutrum erat sit amet blandit. Sed
porttitor dignissim suscipit. Nunc non felis nunc. Phasellus cursus diam eu nunc
viverra, a vestibulum nibh ultrices. Duis iaculis molestie augue, at rutrum lacus
volutpat eget.</p>

<p>Phasellus sit amet tristique risus, tincidunt tempor ante. Nam bibendum odio at
neque iaculis, ut dapibus lorem tempus. In eleifend mi vitae congue pretium. Etiam
ullamcorper accumsan nisi in venenatis. Ut magna diam, iaculis et turpis non,
tincidunt euismod arcu. In malesuada dolor sit amet massa feugiat, eget iaculis ipsum
facilisis. Sed nibh magna, egestas nec accumsan ut, posuere vel lacus.</p>

<ul>
    <li>Fringilla semper eros tincidunt</li>
    <li>
        Nulla nec est in sem dictum
        <ul>
            <li>Ut aliquam erat</li>
            <li>Hendrerit felis</li>
            <li>Amet mattis</li>
        </ul>
    </li>
    <li>fringilla semper eros</li>
</ul>

<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac
turpis egestas. Praesent ut varius leo. Nam vitae nisl eu nisl pretium iaculis ut et
dolor. Praesent ornare consectetur iaculis. Ut dolor sem, malesuada ac rutrum quis,
ultricies sit amet nisl. Quisque eu arcu turpis. Nunc vitae mauris eu massa aliquet
aliquam. Praesent sed hendrerit felis, a convallis est. Vivamus gravida nisl non enim
posuere, ut aliquam felis cursus. Aenean suscipit, nulla sit amet mattis sollicitudin,
mauris arcu sodales velit, at rutrum lorem nulla nec tortor. Pellentesque convallis
ultricies ullamcorper. Phasellus fermentum lobortis tortor in tincidunt. Integer ut
dignissim nisi. Nullam at elit nec magna interdum tempor porttitor at nibh.</p>

<hr />

<p>Suspendisse tincidunt risus in mattis feugiat. Mauris sapien lectus, interdum et
bibendum in, eleifend id purus. Morbi porta nibh arcu, vehicula bibendum mauris
condimentum vitae. Ut porttitor ipsum aliquam nisi euismod sagittis. Morbi volutpat
risus tempus ligula feugiat, nec molestie nulla porttitor. Praesent varius dignissim
felis elementum suscipit. In sodales mauris vitae ligula aliquam, nec iaculis nisi
convallis. Mauris tristique dolor nec lectus auctor pretium. Suspendisse ut commodo
ligula. Integer a nisi molestie quam lobortis imperdiet id ut nulla. Sed feugiat urna
quis augue scelerisque, eu dapibus nunc iaculis. Mauris tristique metus ac luctus
viverra. Phasellus et ultrices turpis, ac eleifend augue.</p>

<?php $page->putTemplate('footer'); ?>