<?php

/**
 * Action helper. Can safely be included inside the scope of every "Vine_Action" script.
 * ---
 * @author  Tell Konkle
 * @date    2016-04-11
 */

// Destroy sensitive data when finish() method is called
$this->setTmp('password');
$this->setTmp('password_again');
$this->setTmp('card_number');
$this->setTmp('card_code');
$this->setTmp('card_cvv');
$this->setTmp('exp_month');
$this->setTmp('exp_year');

// Standardize URL
if ($this->input('url') && stripos($this->input('url'), 'http') !== 0) {
    $this->add('url', 'http://' . $this->input('url'));
}

// Billing country
if ($this->input('bill_country')) {
    // Country: United States
    if ('US' === $this->input('bill_country')) {
        $this->add('bill_province', $this->input('bill_province_us'));
    // Country: Canada
    } elseif ('CA' === $this->input('bill_country')) {
        $this->add('bill_province', $this->input('bill_province_ca'));
    // Country: Other
    } else {
        $this->add('bill_province', $this->input('bill_province_other'));
    }
}

// Shipping country
if ($this->input('ship_country')) {
    // Country: United States
    if ('US' === $this->input('ship_country')) {
        $this->add('ship_province', $this->input('ship_province_us'));
    // Country: Canada
} elseif ('CA' === $this->input('ship_country')) {
        $this->add('ship_province', $this->input('ship_province_ca'));
    // Country: Other
    } else {
        $this->add('ship_province', $this->input('ship_province_other'));
    }
}

// Typical/regular country
if ($this->input('country')) {
    // Country: United States
    if ('US' === $this->input('country')) {
        $this->add('province', $this->input('province_us'));
    // Country: Canada
    } elseif ('CA' === $this->input('country')) {
        $this->add('province', $this->input('province_ca'));
    // Country: Other
    } else {
        $this->add('province', $this->input('province_other'));
    }
}