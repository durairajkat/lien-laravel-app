<?php

// Dependencies
require_once '../include/bootstrap.php';

// Page setup
$page = new WscPage('demo-grid');
$page->setTitle('Responsive Table Example');
$page->setTemplate('header', 'templates/header.php');
$page->setTemplate('footer', 'templates/footer.php');

?>

<?php $page->putTemplate('header'); ?>

<h1 class="h1">
    Responsive Table Example
</h1>

<a class="table-button button">Hello World</a>

<table
    id="test"
    data-widget="table"
    data-title="Optional Table Title"
    data-responsive="true"
    data-search="true"
    data-pages="true"
    data-pages-options="10, 25, 50, 100"
    data-pages-default="10"
    class="stylize flow">
    <thead>
        <tr>
            <th class="sortable">Phasellus</th>
            <th class="sortable">Tincidunt Tempor</th>
            <th class="sortable">Eget Iaculis</th>
            <th class="sortable">Nam</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>egestas nec accumsan ut</td>
            <td>ut aliquam quam</td>
            <td>eu nunc</td>
            <td>32.50</td>
        </tr>
        <tr>
            <td>a diam</td>
            <td>vestibulum</td>
            <td>Nunc non</td>
            <td>98.99</td>
        </tr>
        <tr>
            <td>tortor ac id vulputate sem enim a diam ipsum aliquam nisi euismod</td>
            <td>lobortis tellus</td>
            <td>Duis iaculis</td>
            <td>4.75</td>
        </tr>
        <tr>
            <td>aliquam quam auctor pretium</td>
            <td>odio at</td>
            <td>eleifend mi</td>
            <td>3.39</td>
        </tr>
        <tr>
            <td>aliquam magna urna</td>
            <td>morbi consequat id odio non</td>
            <td>nullam in</td>
            <td>1.58</td>
        </tr>
        <tr>
            <td>eleifend porttitor mi</td>
            <td>purus eget</td>
            <td>gravida lacinia</td>
            <td>4.00</td>
        </tr>
        <tr>
            <td>convallis ipsum sit amet</td>
            <td>suscipit consectetur</td>
            <td>auctor</td>
            <td>4.79</td>
        </tr>
        <tr>
            <td>ante ut ligula malesuada pellentesque</td>
            <td>et magnis dis parturient</td>
            <td>vehicula dui vel urna</td>
            <td>2.49</td>
        </tr>
        <tr>
            <td>aliquet mattis egestas</td>
            <td>aliquam aliquet dictum</td>
            <td>faucibus orci</td>
            <td>1.99</td>
        </tr>
        <tr>
            <td>odio nulla accumsan</td>
            <td>venenatis</td>
            <td>pellentesque id est sit</td>
            <td>9.99</td>
        </tr>
        <tr>
            <td>commodo turpis tortor ut augue</td>
            <td>pulvinar vestibulum</td>
            <td>venenatis</td>
            <td>8.25</td>
        </tr>
        <tr>
            <td>ut turpis in nibh tempor auctor</td>
            <td>a hendrerit justo</td>
            <td>sed ex enim</td>
            <td>19.50</td>
        </tr>
    </tbody>
</table>

<?php $page->putTemplate('footer'); ?>