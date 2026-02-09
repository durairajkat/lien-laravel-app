<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-widgets');
$page->setTitle('Widget & Tool Examples');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<script>
$(document).ready(function() {
    // Setup custom dialog
    $('#custom-dialog').vineDialog({
        hasTitle  : true,
        canClose  : true,
        autoOpen  : false,
        width     : 640,
        height    : 'auto',
        buttons   : {
            'Close' : function() {
                $(this).vineDialog('close');
            },
            'Submit' : {
                text     : 'Submit',
                cssClass : 'fixate',
                type     : 'button',
                onClick  : function() {
                    alert('Custom onClick() event.');
                    $(this).vineDialog('close');
                }
            }
        },
        callers : {
            'hello' : function() {
                alert('Custom hello() caller event.');
                $(this).vineDialog('close');
            }
        },
        onOpen : function() {
            $('#msg-holder').vineSuccessMsg('Example onOpen() event.', 5000);
        },
        onClose : function() {
            $('#msg-holder').vineErrorMsg('Example onClose() event.', {
                'first_name' : 'Field specific error.',
                'last_name'  : 'Field specific error.'
            }, 5000);
        },
        onRefresh : function() {}
    });

    // Open dialog when "Open Custom Dialog" is clicked
    $('#open-custom-dialog').click(function() {
        $('#custom-dialog').vineDialog('open');
    });
});
</script>

<h1 class="h1 line-bottom">
    Widget &amp; Tool Examples
</h1>

<div class="row">
    <div class="flow button left" id="open-custom-dialog">Open Custom Dialog</div>
    <div class="flow button left" data-dialog-open="auto-dialog">Open Auto Dialog</div>
    <div class="flow button left" data-widget="calendar">Calendar Widget</div>
</div>

<div id="msg-holder">
    <!-- Dynamic -->
</div>

<div
    id="custom-dialog"
    class="hide"
    data-auto-open="true"
    data-title="Updated Dialog Title">

    <div class="title">
        Dialog Title
    </div>

    <div class="content">

        <p>
            Suspendisse tincidunt risus in mattis feugiat. Mauris sapien lectus, interdum
            et bibendum in, eleifend id purus. Morbi porta nibh arcu, vehicula bibendum
            mauris condimentum vitae. Ut porttitor ipsum aliquam nisi euismod sagittis.
            Morbi volutpat risus tempus ligula feugiat, nec molestie nulla porttitor.
            Praesent varius dignissim felis elementum suscipit. In sodales mauris vitae
            ligula aliquam, nec iaculis nisi convallis. Mauris tristique dolor nec lectus
            auctor pretium. Suspendisse ut commodo ligula. Integer a nisi molestie quam
            lobortis imperdiet id ut nulla. Sed feugiat urna quis augue scelerisque, eu
            dapibus nunc iaculis. Mauris tristique metus ac luctus viverra. Phasellus et
            ultrices turpis, ac eleifend augue.
        </p>

    </div>

    <div class="actions">
        <button class="button" type="button" data-call="hello">Hello World</button>
    </div>

</div>

<div
    id="auto-dialog"
    data-can-close="true"
    data-widget="dialog"
    data-auto-open="false"
    data-title="Automatically Enabled Dialog With A Super Long Title"
    data-width="640">

    <form method="post" action="action.php">

        <div class="alert failure">
            Please correct the following errors:
            <ul>
                <li>Form field error.</li>
                <li>Form field error.</li>
                <li>Form field error.</li>
            </ul>
        </div>

        <div class="content">

            <p>
                Suspendisse tincidunt risus in mattis feugiat. Mauris sapien lectus, interdum
                et bibendum in, eleifend id purus. Morbi porta nibh arcu, vehicula bibendum
                mauris condimentum vitae. Ut porttitor ipsum aliquam nisi euismod sagittis.
                Morbi volutpat risus tempus ligula feugiat, nec molestie nulla porttitor.
                Praesent varius dignissim felis elementum suscipit. In sodales mauris vitae
                ligula aliquam, nec iaculis nisi convallis. Mauris tristique dolor nec lectus
                auctor pretium. Suspendisse ut commodo ligula. Integer a nisi molestie quam
                lobortis imperdiet id ut nulla. Sed feugiat urna quis augue scelerisque, eu
                dapibus nunc iaculis. Mauris tristique metus ac luctus viverra. Phasellus et
                ultrices turpis, ac eleifend augue.
            </p>

            <div class="row">
                <label>Field Label</label>
                <input type="text" />
            </div>

            <div class="row">
                <div class="col-8 pad">
                    <label>Field Label</label>
                    <input type="file" />
                </div>
                <div class="col-4 pad">
                    <label>Field Label</label>
                    <input type="text" />
                </div>
            </div>

        </div>

        <div class="actions">
            <button class="button" type="button" data-call="close">Close</button>
            <button class="button" type="submit">Submit</button>
        </div>

    </form>

</div>

<?php $page->putTemplate('footer'); ?>