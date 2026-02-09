<?php

// Only use to debug UPS integration! Always set to FALSE when finished debugging!
defined('SHIPPING_DEBUG') or define('SHIPPING_DEBUG', FALSE);

/**
 * Base class for shipping module.
 * ---
 * @author  Tell Konkle
 * @date    2019-03-07
 */
abstract class Shipping_Base
{
    /**
     * Shipping address objects.
     * ---
     * @var  object  Instance of Shipping_Address.
     */
    protected $from = NULL;
    protected $to   = NULL;

    /**
     * Packages in shipment.
     * ---
     * @var  object  Instance of Shipping_Packages.
     */
    protected $packages = NULL;

    /**
     * When TRUE, all API requests are run in test mode.
     * ---
     * @var  bool
     */
    protected $testMode = FALSE;

    /**
     * Error messages returned from API or address objects.
     * ---
     * @var  array
     */
    protected $errors = NULL;

    /**
     * Run API requests in test mode?
     * ---
     * @param   bool  TRUE for test mode. When FALSE, requests are made in production.
     * @return  void
     */
    public function setTestMode($test)
    {
        $this->testMode = (bool) $test;
    }

    /**
     * Set an address error.
     * ---
     * @param   string  Error message.
     * @return  void
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }

    /**
     * Get an address error.
     * ---
     * @return  array|bool  FALSE if no errors found, array otherwise.
     */
    public function getErrors()
    {
        return $this->errors ? $this->errors : FALSE;
    }

    /**
     * Is the address valid?
     * ---
     * @return  bool
     */
    public function isValid()
    {
        return $this->errors ? FALSE : TRUE;
    }

    /**
     * Set the ship from address.
     * ---
     * @param   object  Instance of Shipping_Address.
     * @return  void
     */
    public function setFrom(Shipping_Address $address)
    {
        // Verify
        if ( ! $address->isValid()) {
            $this->setError('Invalid ship from address.');
        }

        // Save
        $this->from = $address;
    }

    /**
     * Set the ship to address.
     * ---
     * @param   object  Instance of Shipping_Address.
     * @return  void
     */
    public function setTo(Shipping_Address $address)
    {
        // Verify
        if ( ! $address->isValid()) {
            $this->setError('Invalid ship to address.');
        }

        // Save
        $this->to = $address;
    }

    /**
     * Set all of the packed boxes that are part of this shipment.
     * ---
     * @param   object  Instance of Shipping_Packages.
     * @return  void
     */
    public function setPackages(Shipping_Packages $packages)
    {
        if ($packages->getPackages()) {
            $this->packages = $packages->getPackages();
        }
    }

    /**
     * Convert ounces to pounds.
     * ---
     * @param   mixed   The ounces to convert.
     * @param   int     How many decimal places to round result.
     * @return  string  A well formatted number in '0.00' format.
     */
    protected function ouncesToPounds($oz, $round = 2)
    {
        if ($oz && (($oz / 16) >= 0.1)) {
            return number_format(round($oz / 16, $round), $round, '.', '');
        } else {
            return '0.1';
        }
    }

    /**
     * Sort the returned shipping rates from the cheapest to the most expensive.
     * ---
     * @param   mixed
     * @param   mixed
     * @return  bool
     */
    protected static function sortRates($a, $b)
    {
        if ($a['price'] == $b['price']) {
            return 0;
        }

        return $a['price'] < $b['price'] ? -1 : 1;
    }
}
