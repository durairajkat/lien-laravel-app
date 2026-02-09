<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-grid');
$page->setTitle('Helper Styles');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<h1 class="h1 line-bottom">
    Helper Styles
</h1>

<div class="alert caution">
    For a complete list of helper classes, please refer to <b>helpers.css</b>.
</div>

<table data-widget="table" class="stylize flow">
    <thead>
        <tr>
            <th>Prepends</th>
            <th>CSS Class</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>None</td>
            <td>.bb</td>
            <td>Add border-box styling to element and all sub-elements.</td>
        </tr>
        <tr>
            <td>None</td>
            <td>.strip</td>
            <td>Strip (reset) all margins, padding, and styles of an element.</td>
        </tr>
        <tr>
            <td>None</td>
            <td>.flow</td>
            <td>Add top margin to element for space and breathing room.</td>
        </tr>
        <tr>
            <td>None</td>
            <td>.halt</td>
            <td>Remove top margin on an element.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.hide</td>
            <td>Hide an element with display: none;</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.text-left</td>
            <td>Left text alignment.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.text-center</td>
            <td>Center text alignment.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.text-right</td>
            <td>Right text alignment.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.clickable</td>
            <td>Add pointer mouse cursor to a non-link element.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-less</td>
            <td>Make font smaller than inherited size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-less-1</td>
            <td>Alias of .size-less</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-less-2</td>
            <td>Make font smaller than inherited size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-less-3</td>
            <td>Make font smaller than inherited size (use with caution).</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-more</td>
            <td>Make font bigger than inherited size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-more-1</td>
            <td>Alias of .size-more.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-more-2</td>
            <td>Make font bigger than inherited size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-more-3</td>
            <td>Make font bigger than inherited size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-1</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-2</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-3</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-4</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-5</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.size-6</td>
            <td>An exact font size.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h1</td>
            <td>Use on &lt;h1&gt; elements.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h2</td>
            <td>Use on &lt;h2&gt; elements.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h3</td>
            <td>Use on &lt;h3&gt; elements.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h4</td>
            <td>Use on &lt;h4&gt; elements.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h5</td>
            <td>Use on &lt;h5&gt; elements.</td>
        </tr>
        <tr>
            <td>.lg- .md- .sm-</td>
            <td>.h6</td>
            <td>Use on &lt;h6&gt; elements.</td>
        </tr>
    </tbody>
</table>

<?php $page->putTemplate('footer'); ?>