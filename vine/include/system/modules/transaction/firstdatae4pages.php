<?php

/**
 * Wrapper class for the First Data E4 Hosted Payment Pages system.
 *
 * @author  Tell Konkle
 * @date    2014-10-09
 */
class Transaction_FirstDataE4Pages
{
    /**
     * Test/production redirect URLs.
     */
    const REDIRECT_SANDBOX    = 'https://demo.globalgatewaye4.firstdata.com/payment';
    const REDIRECT_PRODUCTION = 'https://checkout.globalgatewaye4.firstdata.com/payment';

    /**
     * Instance of Vine_Request().
     * @var  object  Instance of Vine_Request()
     * @see  __construct()
     */
    protected $req = NULL;

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * The First Data transaction and response keys.
     * @var  string
     * @see  __construct()
     */
    protected $password = NULL;
    protected $response = NULL;

    /**
     * Parameters to use when redirecting to First Data.
     * @var  array
     * @see  getRedirectUrl()
     */
    protected $params = array
    (
        // Essential fields
        'x_login'        => NULL,           // Login ID
        'x_fp_sequence'  => NULL,           // Security code used in hash calculations
        'x_fp_timestamp' => NULL,           // Unix timestamp
        'x_amount'       => '0.00',         // The transaction amount
        'x_fp_hash'      => NULL,           // Auto-generated hash (handled by library)
        'x_show_form'    => 'PAYMENT_FORM', // Do not modify

        // Processing fields
        'x_test_request' => TRUE,           // Test mode?
        'x_type'         => 'AUTH_CAPTURE', // Transaction type
        'x_gateway_id'   => NULL,           // Gateway terminal ID

        // Receipt page fields
        'x_receipt_link_method' => 'AUTO-POST',            // Automatically return to website
        'x_receipt_link_text'   => 'Complete Transaction', // Complete transaction button
        'x_receipt_link_url'    => NULL,                   // The URL to the receipt page

        // Email setting fields
        'x_merchant_email' => NULL,  // Email address of merchant

        // Transaction data fields
        'x_currency_code' => 'USD', // 3 letter ISO currency code

        // Reference
        'x_cust_id'     => NULL, // Database ID
        'x_invoice_num' => NULL, // Order number
        'x_description' => NULL, // Description of transaction

        // Billiing address
        'x_first_name' => NULL, // Billing first name
        'x_last_name'  => NULL, // Billing last name
        'x_company'    => NULL, // Billing company name
        'x_address'    => NULL, // Billing address 1 & 2
        'x_city'       => NULL, // Billing city
        'x_state'      => NULL, // Billing state/province
        'x_zip'        => NULL, // Billing zip/postal
        'x_country'    => NULL, // Billing country (must be full name)
        'x_phone'      => NULL, // Billing phone number

        // Shipping address
        'x_ship_to_first_name' => NULL, // Shipping first name
        'x_ship_to_last_name'  => NULL, // Shipping last name
        'x_ship_to_company'    => NULL, // Shipping company name
        'x_ship_to_address'    => NULL, // Shipping address 1 & 2
        'x_ship_to_city'       => NULL, // Shipping city
        'x_ship_to_state'      => NULL, // Shipping state/province
        'x_ship_to_zip'        => NULL, // Shipping zip/postal
        'x_ship_to_country'    => NULL, // Shipping country (must be full name)

        // Customer
        'x_customer_ip' => NULL,
        'x_email'       => NULL,
    );

    /**
     * Class constructor
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct(
        $login,
        $password,
        $response,
        $sequence,
        $timestamp,
        $testMode = FALSE
    ) {
	    $this->params['x_login']        = $login;
	    $this->params['x_fp_sequence']  = $sequence;
        $this->params['x_fp_timestamp'] = $timestamp;
        $this->params['x_test_request'] = (bool) $testMode;
        $this->password = $password;
        $this->response = $response;
        $this->req      = new Vine_Request();
    }

    /**
     * Specify test or production mode.
     * @param   bool  When FALSE, production mode is assumed.
     * @return  void
     */
    public function setTestMode($mode)
    {
        if ( ! $mode) {
            $this->params['x_test_request'] = FALSE;
        } else {
            $this->params['x_test_request'] = TRUE;
        }
    }

    /**
     * Set the order or invoice number for this transaction.
     * @param   string
     * @return  void
     */
    public function setOrderNumber($number)
    {
        $this->params['x_invoice_num'] = $number;
    }

    /**
     * Set the merchant's email address.
     * @param   string
     * @return  void
     */
    public function setBusinessEmail($email)
    {
        $this->params['x_merchant_email'] = $email;
    }

    /**
     * Set the order total and applicable currency code.
     * @param   float|string|int
     * @param   string
     * @return  void
     */
    public function setAmount($amount, $currency = 'USD')
    {
        $this->params['x_amount']        = number_format((float) $amount, 2, '.', '');
        $this->params['x_currency_code'] = $currency;
    }

    /**
     * Set the return URL. This is the URL that the buyer will get redirected to once
     * they've completed their purchase at First Data and choose to go back to the
     * merchant's website.
     *
     * @param   string
     * @return  void
     */
    public function setReturnUrl($url)
    {
        $this->params['x_receipt_link_url'] = $url;
    }

    /**
     * Set the text that will be displayed in the return to merchant button.
     * @param   string
     * @return  void
     */
    public function setCompleteOrderText($text)
    {
        $this->params['x_receipt_link_text'] = $text;
    }

    /**
     * Set the billing first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->params['x_first_name'] = $firstName;
    }

    /**
     * Set the billing last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->params['x_last_name'] = $lastName;
    }

    /**
     * Set the billing company.
     * @param   string
     * @return  void
     */
    public function setCompany($company)
    {
        $this->params['x_company'] = $company;
    }

    /**
     * Set billing address 1.
     * @param   string
     * @return  void
     */
    public function setAddress1($address)
    {
        $this->params['x_address'] = $address;
    }

    /**
     * Set billing address 2.
     * @param   string
     * @return  void
     */
    public function setAddress2($address)
    {
        $this->params['x_address'] .= ' ' . $address;
    }

    /**
     * Set billing city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->params['x_city'] = $city;
    }

    /**
     * Set billing state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->params['x_state'] = $province;
    }

    /**
     * Set billing zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        $this->params['x_zip'] = $postal;
    }

    /**
     * Set billing country.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        // All of the valid ISO 3166-1 countries
        $countries = require VINE_PATH . 'countries.php';

        // Use the full country name
        if (isset($countries[$country])) {
            $this->params['x_country'] = $countries[$country][0];
        }
    }

    /**
     * Set billing email address.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        $this->params['x_email'] = $email;
    }

    /**
     * Get the URL to use to redirect buyer to PayPal.
     * @return  string
     */
    public function getRedirectUrl()
    {
        // The base redirect URL
        $url    = $this->_getRedirectUrl();
        $params = '';

        // Generate additional data
        $this->params['x_fp_hash']     = $this->_getRedirectHash();
        $this->params['x_customer_ip'] = Vine_Request::getIp();

        // Loop through all of the parameters and compile URL
        foreach ($this->params as $param => $value) {
            if (TRUE === $value) {
                $params .= '&' . $param . '=TRUE';
            } elseif (FALSE === $value) {
                $params .= '&' . $param . '=FALSE';
            } elseif (NULL !== $value) {
                $value = Vine_Unicode::toAscii(trim($value));
                $params .= '&' . $param . '=' . urlencode($value);
            }
        }

        // (string) The final redirect URL
        return $url . '?' . ltrim($params, '&');
    }

    /**
     * Verify a payment notification.
     * @return  bool  TRUE if all provided data is valid, FALSE otherwise.
     */
    public function verifyNotification()
    {
        // (bool)
        $hash = $this->req->input('x_MD5_Hash') === $this->_getResponseHash();

        // Do not verify further when in test mode
        if ($this->params['x_test_request']) {
            return $hash;
        // Verify response type
        } else {
            return $hash && '1' === $this->req->input('x_response_code');
        }
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
     * Get the transaction ID returned from First Data.
     * @return  string
     */
    public function getTransactionId()
    {
        return $this->req->input('x_trans_id');
    }

    /**
     * Get the base URL to First Data.
     * @return  string
     */
    private function _getRedirectUrl()
    {
        if (TRUE === $this->params['x_test_request']) {
            return self::REDIRECT_PRODUCTION;
        } else {
            return self::REDIRECT_PRODUCTION;
        }
    }

    /**
     * Get the FP hash to use to send.
     * @return  string
     */
    private function _getRedirectHash()
    {
        // Compile the hash
        $hash = $this->params['x_login'] . '^'
              . $this->params['x_fp_sequence'] . '^'
              . $this->params['x_fp_timestamp'] . '^'
              . $this->params['x_amount'] . '^'
              . $this->params['x_currency_code'];

        // (string)
        return hash_hmac('md5', $hash, $this->password);
    }

    /**
     * Get the FP to verify in a response.
     * @return  string
     */
    private function _getResponseHash()
    {
        // Compile the hash
        $hash = $this->response
              . $this->params['x_login']
              . $this->req->input('x_trans_id')
              . $this->req->input('x_amount');

        // (string)
        return md5($hash);
    }
}