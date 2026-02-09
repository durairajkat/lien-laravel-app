<?php

/**
 * Wrapper class for the PayPal Standard system (with IPN support).
 *
 * @author  Tell Konkle
 * @date    2013-04-25
 */
class Transaction_PayPalStandard
{
    /**
     * Test/production buyer redirect URLs. 
     */
    const REDIRECT_SANDBOX    = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    const REDIRECT_PRODUCTION = 'https://www.paypal.com/cgi-bin/webscr';

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * Parameters to use when redirecting buyer to PayPal.
     * @var  array
     * @see  getRedirectUrl()
     */
    protected $goToParams = array
    (
        'bn'            => NULL,                // PayPal partner code (BN code)
        'business'      => NULL,                // Merchant email address
        'upload'        => 1,                   // Indicates 3rd party shopping cart
        'no_shipping'   => 1,                   // Don't prompt for shipping address
        'no_note'       => 1,                   // Don't prompt for buyer notes
        'paymentaction' => 'sale',              // 'sale', 'authorization', or 'order'
        'notify_url'    => NULL,                // IPN URL
        'cancel_return' => NULL,                // Cancel URL
        'return'        => NULL,                // Return URL
        'rm'            => 2,                   // Return method (POST)
        'cbt'           => 'Complete Purchase', // Return text
        'currency_code' => 'USD',               // Valid PayPal supported currency code
        'custom'        => NULL,                // Custom pass-through variable
        'invoice'       => NULL,                // Pass-through variable with order number
        'amount_1'      => 0.00,                // Total charge amount of cart
        'item_name_1'   => NULL,                // Name of cart
        'first_name'    => NULL,                // Billing first name
        'last_name'     => NULL,                // Billing last name
        'address1'      => NULL,                // Billing address (line 1)
        'address2'      => NULL,                // Billing address (line 2)
        'city'          => NULL,                // Billing city
        'state'         => NULL,                // Billing state (US only)
        'country'       => 'US',                // Billing country
        'zip'           => NULL,                // Billing postal code
        'email'         => NULL,                // Billing email address
    );

    /**
     * Test mode?
     * @var  bool
     * @see  setTestMode()
     */
    protected $testMode = FALSE;

    /**
     * Specify test or production mode.
     * @param   bool  When FALSE, production mode is assumed.
     * @return  void
     */
    public function setTestMode($mode)
    {
        if ( ! $mode) {
            $this->testMode = FALSE;
        } else {
            $this->testMode = TRUE;
        }
    }

    /**
     * Set the PayPal partner code (BN code).
     * @param   string
     * @return  void
     */
    public function setPartnerCode($code)
    {
        $this->goToParams['bn'] = $code;      
    }

    /**
     * Set the merchant's email address.
     * @param   string
     * @return  void
     */
    public function setBusinessEmail($email)
    {
        $this->goToParams['business'] = $email;
    }

    /**
     * Set the name of the cart. Needed since individual items in cart won't get
     * displayed.
     *
     * @param   string
     * @return  void
     */
    public function setCartName($name)
    {
        $this->goToParams['item_name_1'] = $name;
    }

    /**
     * Set the order total and applicable currency code.
     * @param   float|string|int
     * @param   string
     * @return  void
     */
    public function setAmount($amount, $currency = 'USD')
    {
        $this->goToParams['amount_1']      = number_format($amount, 2, '.', '');
        $this->goToParams['currency_code'] = $currency;
    }

    /**
     * Set the URL that PayPal will redirect buyer to if buyer cancels the checkout
     * process.
     *
     * @param   string
     * @return  void
     */
    public function setCancelUrl($url)
    {
        $this->goToParams['cancel_return'] = $url;
    }

    /**
     * Set the instant payment notification (IPN) URL. This is the URL that PayPal will
     * silently post data to when buyer completes a purchase.
     *
     * @param   string
     * @return  void
     */
    public function setNotificationUrl($url)
    {
        $this->goToParams['notify_url'] = $url;
    }

    /**
     * Set the return URL. This is the URL that the buyer will get redirected to once
     * they've completed their purchase with PayPal and choose to go back to the
     * merchant's website.
     *
     * @param   string
     * @return  void
     */
    public function setReturnUrl($url)
    {
        $this->goToParams['return'] = $url;
    }

    /**
     * Set custom data that will be posted with the data in the return URL and/or
     * notification URL.
     *
     * @param   string
     * @return  void
     * @see     setNotificationUrl()
     * @see     setReturnUrl()
     */
    public function setCustomData($data)
    {
        $this->goToParams['custom'] = $data;
    }

    /**
     * Set the text that will be displayed in the return to merchant button.
     * @param   string
     * @return  void
     */
    public function setCompleteOrderText($text)
    {
        $this->goToParams['cbt'] = $text;
    }

    /**
     * Set the billing first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->goToParams['first_name'] = $firstName;
    }

    /**
     * Set the billing last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->goToParams['last_name'] = $lastName;
    }

    /**
     * Set billing address 1.
     * @param   string
     * @return  void
     */
    public function setAddress1($address)
    {
        $this->goToParams['address1'] = $address;
    }

    /**
     * Set billing address 2.
     * @param   string
     * @return  void
     */
    public function setAddress2($address)
    {
        $this->goToParams['address2'] = $address;
    }

    /**
     * Set billing city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->goToParams['city'] = $city;
    }

    /**
     * Set billing state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->goToParams['state'] = $province;
    }

    /**
     * Set billing country.
     * @param   string
     * @return  void 
     */
    public function setCountry($country)
    {
        $this->goToParams['country'] = $country;
    }

    /**
     * Set billing zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        $this->goToParams['zip'] = $postal;
    }

    /**
     * Get the URL to use to redirect buyer to PayPal.
     * @return  string
     */
    public function getRedirectUrl()
    {
        // The base redirect URL
        $url = $this->_getRedirectUrl() . '?cmd=_cart';

        // Don't include state in address if outside of United States
        if ('US' !== $this->goToParams['country']) {
            $this->goToParams['state'] = NULL;
        }

        // Loop through all of the parameters and compile URL
        foreach ($this->goToParams as $param => $value) {
            if (NULL !== $value) {
                $url .= '&' . $param . '=' . urlencode($value);
            }
        }

        // (string) The final redirect URL
        return $url;
    }

    /**
     * Verify an Instant Payment Notification (IPN).
     * @return  bool  TRUE if all provided data is valid, FALSE otherwise.
     */
    public function verifyNotification()
    {
        // See if magic quotes is enabled
        $magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc();

        // Begin preparing cURL request
        $req = 'cmd=_notify-validate';

        // Continue compiling cURL request
        if (is_array($_POST) && ! empty($_POST)) {
            // Loop through all POST data and append to cURL request
            foreach ($_POST as $key => $value) {
                if ($magic) {
                    $req .= '&' . $key . '=' . urlencode(stripslashes($value));
                } else {
                    $req .= '&' . $key . '=' . urlencode($value);
                }
            }
        }

        // Prepare cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_getRedirectUrl());
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        // Execute cURL request
        $response = curl_exec($ch);

        // Close cURL
        curl_close($ch);

        // Response will be "VERIFIED" or "INVALID"
        return 'VERIFIED' === trim($response);
    }

    /**
     * Set an end-user error.
     * @param   string
     * @return  void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Get an end-user error.
     * @return  string  NULL if no errors. 
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * See if everything is valid so far (no errors).
     * @return  bool
     */
    public function isValid()
    {
        return NULL === $this->error;
    }

    /**
     * Get the base URL to PayPal.
     * @return  string
     */
    private function _getRedirectUrl()
    {
        if (TRUE === $this->testMode) {
            return self::REDIRECT_SANDBOX;
        } else {
            return self::REDIRECT_PRODUCTION;
        }
    }
}