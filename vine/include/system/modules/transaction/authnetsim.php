<?php

/**
 * Wrapper class for SIM API for Authorize.Net.
 *
 * @author  Tell Konkle
 * @date    2016-04-12
 */
class Transaction_AuthNetSim
{
    /**
     * Handoff URLs.
     */
    const HANDOFF_LIVE = 'https://secure2.authorize.net/gateway/transact.dll';
    const HANDOFF_TEST = 'https://test.authorize.net/gateway/transact.dll';

    /**
     * Instance of Vine_Request().
     * @var  object  Instance of Vine_Request()
     * @see  __construct()
     */
    protected $req = NULL;

    /**
     * API credentials.
     * @var  string
     * @see  __construct()
     */
    protected $loginId        = NULL;
    protected $transactionKey = NULL;
    protected $response       = NULL;
    protected $testMode       = TRUE;

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * Parameters to use when redirecting buyer to Authorize.Net.
     * @var  array
     */
    protected $params = array
    (
        'x_address'                  => NULL,
        'x_amount'                   => NULL,
        'x_currency_code'            => NULL,
        'x_background_url'           => NULL,
        'x_card_num'                 => NULL, // Do not use
        'x_city'                     => NULL,
        'x_color_background'         => NULL,
        'x_color_link'               => NULL,
        'x_color_text'               => NULL,
        'x_company'                  => NULL,
        'x_country'                  => NULL,
        'x_cust_id'                  => NULL,
        'x_customer_ip'              => NULL,
        'x_description'              => NULL,
        'x_delim_data'               => NULL,
        'x_duplicate_window'         => NULL,
        'x_duty'                     => NULL,
        'x_email'                    => NULL,
        'x_email_customer'           => 'N',
        'x_fax'                      => NULL,
        'x_first_name'               => NULL,
        'x_footer_email_receipt'     => NULL,
        'x_footer_html_payment_form' => NULL,
        'x_footer_html_receipt'      => NULL,
        'x_fp_hash'                  => NULL,
        'x_fp_sequence'              => NULL,
        'x_fp_timestamp'             => NULL,
        'x_freight'                  => NULL,
        'x_header_email_receipt'     => NULL,
        'x_header_html_payment_form' => NULL,
        'x_header_html_receipt'      => NULL,
        'x_invoice_num'              => NULL,
        'x_last_name'                => NULL,
        'x_line_item'                => NULL,
        'x_login'                    => NULL,
        'x_logo_url'                 => NULL,
        'x_method'                   => 'CC',
        'x_phone'                    => NULL,
        'x_po_num'                   => NULL,
        'x_receipt_link_method'      => 'POST',
        'x_receipt_link_text'        => NULL,
        'x_receipt_link_url'         => NULL,
        'x_recurring_billing'        => NULL,
        'x_relay_response'           => NULL,
        'x_relay_url'                => NULL,
        'x_rename'                   => NULL,
        'x_ship_to_address'          => NULL,
        'x_ship_to_company'          => NULL,
        'x_ship_to_country'          => NULL,
        'x_ship_to_city'             => NULL,
        'x_ship_to_first_name'       => NULL,
        'x_ship_to_last_name'        => NULL,
        'x_ship_to_state'            => NULL,
        'x_ship_to_zip'              => NULL,
        'x_show_form'                => 'PAYMENT_FORM',
        'x_state'                    => NULL,
        'x_tax'                      => NULL,
        'x_tax_exempt'               => NULL,
        'x_test_request'             => NULL,
        'x_trans_id'                 => NULL,
        'x_type'                     => 'AUTH_CAPTURE',
        'x_version'                  => NULL,
        'x_zip'                      => NULL,
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
        $loginId,
        $transactionKey,
        $response,
        $sequence,
        $timestamp,
        $testMode = FALSE
    ) {
        $this->req                      = new Vine_Request();
        $this->loginId                  = $loginId;
        $this->transactionKey           = $transactionKey;
        $this->response                 = $response;
        $this->testMode                 = (bool) $testMode;
        $this->params['x_test_request'] = (bool) $testMode;
        $this->params['x_login']        = $loginId;
        $this->params['x_fp_sequence']  = $sequence;
        $this->params['x_fp_timestamp'] = $timestamp;
    }

    /**
     * Manually set a parameter.
     * @param   string  The parameter name.
     * @param   mixed   The parameter value.
     * @return  void
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * Set the order or invoice number for this transaction, as well as the database ID.
     * @param   string  The order's invoice number.
     * @return  void
     */
    public function setOrderNumber($number)
    {
        if (strlen(trim($number))) {
            $this->params['x_invoice_num'] = trim($number);
        }
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
        $this->params['x_currency_code'] = trim($currency);
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
        if (Vine_Verify::url(trim($url))) {
            $this->params['x_receipt_link_url'] = trim($url);
            $this->params['x_relay_url']        = trim($url);
            $this->params['x_relay_response']   = 'TRUE';
        }
    }

    /**
     * Set the text that will be displayed in the return to merchant button.
     * @param   string
     * @return  void
     */
    public function setCompleteOrderText($text)
    {
        if (strlen(trim($text))) {
            $this->params['x_receipt_link_text'] = trim($text);
        }
    }

    /**
     * Set the billing first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        if (strlen(trim($firstName))) {
            $this->params['x_first_name'] = trim($firstName);
        }
    }

    /**
     * Set the billing last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        if (strlen(trim($lastName))) {
            $this->params['x_last_name'] = trim($lastName);
        }
    }

    /**
     * Set the billing company.
     * @param   string
     * @return  void
     */
    public function setCompany($company)
    {
        if (strlen(trim($company))) {
            $this->params['x_company'] = trim($company);
        }
    }

    /**
     * Set billing address 1.
     * @param   string
     * @return  void
     */
    public function setAddress1($address)
    {
        if (strlen(trim($address))) {
            $this->params['x_address'] = trim($address);
        }
    }

    /**
     * Set billing address 2.
     * @param   string
     * @return  void
     */
    public function setAddress2($address)
    {
        // Void, no field applicable
    }

    /**
     * Set billing city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        if (strlen(trim($city))) {
            $this->params['x_city'] = trim($city);
        }
    }

    /**
     * Set billing state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        if (strlen(trim($province))) {
            $this->params['x_state'] = trim($province);
        }
    }

    /**
     * Set billing zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        if (strlen(trim($postal))) {
            $this->params['x_zip'] = trim($postal);
        }
    }

    /**
     * Set billing country.
     * @param   string
     * @return  void 
     */
    public function setCountry($country)
    {
        if (strlen(trim($country))) {
            $this->params['x_country'] = trim($country);
        }
    }

    /**
     * Set billing email address.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        if (Vine_Verify::email(trim($email))) {
            $this->params['x_email'] = trim($email);
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
     * Get the URL to use to redirect buyer to Authorize.Net.
     * @return  string
     */
    public function generateForm()
    {
        // First, generate the secure hash
        $this->_generateHash();

        // Test URL or live URL?
        $url = $this->testMode ? self::HANDOFF_TEST : self::HANDOFF_LIVE;

        // Start compiling form markup
        $markup = '<form id="authnet" method="post" action="' . $url . '">';

        // Loop through all parameters
        foreach ($this->params as $k => $v) {
            // Skip this field
            if ( ! strlen($v)) {
                continue;
            }

            // Compile this parameter field
            $markup .= '<input '
                     . '    type="hidden" '
                     . '    name="' . $k . '" '
                     . '    value="' . Vine_Html::output($v) .  '" '
                     . '/>';
        }

        // Finish compiling form markup
        $markup .= '<input '
                 . '    type="submit" '
                 . '    value="Click here if you\'re not redirected..." '
                 . '/>'
                 . '</form>'
                 . '<script>'
                 . 'window.onload = function() { '
                 . "    document.getElementById('authnet').submit(); "
                 . '};'
                 . '</script>';

        // (string) HTML-ready result
        return $markup;
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
     * Generate secure fingerprint hash. Concatenate the following fields with ' ^ '.
     *
     * - API login ID (x_login)
     * - Sequence number (x_fp_sequence)
     * - UTC timestamp in seconds (x_fp_timestamp)
     * - Amount (x_amount)
     * - Currency (x_currency_code)
     *
     * Example input:
     * "authnettest^789^67897654^10.50^USD"
     */
    private function _generateHash()
    {
        // Compile hash
        $hash = array
        (
            'x_login'         => $this->params['x_login'],
            'x_fp_sequence'   => $this->params['x_fp_sequence'],
            'x_fp_timestamp'  => $this->params['x_fp_timestamp'],
            'x_amount'        => $this->params['x_amount'],
            'x_currency_code' => $this->params['x_currency_code'],
        );

        // Generate final HMAC hash
        $this->params['x_fp_hash'] = hash_hmac('md5', implode('^', $hash), $this->transactionKey);
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
        return strtoupper(md5($hash));
    }
}