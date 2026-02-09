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

<script>
/**
 * Advanced calling object. You can specify custom functions to run at various
 * points in the form submission process.
 */
var test = {
    /**
     * An example function that is run right before form is submitted via AJAX.
     * @return  void
     */
    beforeSubmit : function() {
        $.vine.log('Form is about to be submitted.');
    },

    /**
     * An example function that is run right after submitted form fails.
     * @param   object  The response data (if any).
     * @return  void
     */
    errorResult : function(data) {
        $.vine.log('Form was submitted and has error result.');
    },

    /**
     * An example function that is run right after submitted form succeeds.
     * @param   object  The response data (if any).
     * @return  void
     */
    successResult : function(data) {
        $.vine.log('Form was submitted and has success result.');
    }
};
</script>

<h1 class="h1">
    Form Examples
</h1>

<form
    id="form-example" method="post" action="action/demo-forms"
    data-widget="form"
    data-success-auto-hide="5000"
    data-success-redirect="false"
    data-error-auto-hide="false"
    data-error-redirect="false"
    data-error-default="Invalid Request"
    data-scroll-to-msg="true"
    data-msg-container="form-example-msgs"
    data-call-submit="test.beforeSubmit"
    data-call-error="test.errorResult"
    data-call-success="test.successResult">

    <div class="fieldset flow">

        <div class="legend">
            Fieldset Name
            <span>Text Or <a href="http://www.google.com">Links</a> Can Go Here</span>
        </div>

        <div class="row">
            <div class="col-12 pad">
                <label>Field Label</label>
                <textarea data-widget="calendar"></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-4 pad">
                <label>First Name</label>
                <input id="first-name" type="text" name="first_name" />
            </div>
            <div class="col-sm-12 col-4 pad">
                <label>Last Name</label>
                <input type="text" name="last_name" />
            </div>
            <div class="col-sm-12 col-4 pad">
                <label>Province</label>
                <select name="province" id="province">
                    <option value="A">Praesent Varius Dignissim Convallis</option>
                    <option value="B">Quam Lobortis</option>
                    <option value="C">Nunc Vitae Mauris</option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12 pad">
                <label>Field Label</label>
                <textarea></textarea>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-8 pad">
                <label>Field Label</label>
                <select>
                    <option>Praesent Varius Dignissim Convallis</option>
                    <option>Quam Lobortis</option>
                    <option>Nunc Vitae Mauris</option>
                </select>
            </div>
            <div class="col-sm-12 col-4 pad">
                <label>Field Name</label>
                <input
                    type="checkbox"
                    name="checkbox_a"
                    value="Hi"
                    checked="checked"
                />
                <input
                    type="checkbox"
                    name="checkbox_b"
                    value="Bye"
                />
                <input
                    type="radio"
                    name="favorite_vocalist"
                    value="Mary Fahl"
                />
                <input
                    type="radio"
                    checked="checked"
                    name="favorite_vocalist"
                    value="Sarah Brightman"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 col-8 pad">
                <label>Image Upload</label>
                <input type="file" name="upload" />
            </div>
            <div class="col-sm-4 col-4 pad">
                <label>Field Label</label>
                <select>
                    <option>Praesent Varius Dignissim Convallis</option>
                    <option>Quam Lobortis</option>
                    <option>Nunc Vitae Mauris</option>
                </select>
            </div>
        </div>

    </div>

    <div class="row">
        <button type="submit" class="flow button left">Submit Form</button>
        <button type="button" class="flow button left fixate">Fixate</button>
        <button type="button" class="flow button left success">Success</button>
        <button type="button" class="flow button left caution">Caution</button>
        <button type="button" class="flow button left failure">Failure</button>
    </div>

</form>

<?php $page->putTemplate('footer'); ?>