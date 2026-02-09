<?php

/**
 * Ajax helper. Can safely be included inside the scope of every ajax script.
 * ---
 * @author  Tell Konkle
 * @date    2016-04-11
 */

// Force script to always be considered an AJAX request
Vine_Request::forceAjax(TRUE);

// Prepare objects
$req = new Vine_Request();
$db  = Vine_Registry::getObject('db');

// Standardize URL
if ($req->input('url') && stripos($req->input('url'), 'http') !== 0) {
    $req->add('url', 'http://' . $req->input('url'));
}

// Billing country
if ($req->input('bill_country')) {
    // Country: United States
    if ('US' === $req->input('bill_country')) {
        $req->add('bill_province', $req->input('bill_province_us'));
    // Country: Canada
    } elseif ('CA' === $req->input('bill_country')) {
        $req->add('bill_province', $req->input('bill_province_ca'));
    // Country: Other
    } else {
        $req->add('bill_province', $req->input('bill_province_other'));
    }
}

// Shipping country
if ($req->input('ship_country')) {
    // Country: United States
    if ('US' === $req->input('ship_country')) {
        $req->add('ship_province', $req->input('ship_province_us'));
    // Country: Canada
} elseif ('CA' === $req->input('ship_country')) {
        $req->add('ship_province', $req->input('ship_province_ca'));
    // Country: Other
    } else {
        $req->add('ship_province', $req->input('ship_province_other'));
    }
}

// Typical/regular country
if ($req->input('country')) {
    // Country: United States
    if ('US' === $req->input('country')) {
        $req->add('province', $req->input('province_us'));
    // Country: Canada
    } elseif ('CA' === $req->input('country')) {
        $req->add('province', $req->input('province_ca'));
    // Country: Other
    } else {
        $req->add('province', $req->input('province_other'));
    }
}