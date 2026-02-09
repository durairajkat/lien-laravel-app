<?php

/**
 * Wrapper class for the PayPal Payments Pro Direct Payment API.
 *
 * @author  Tell Konkle
 * @date    2015-06-11
 */
class Transaction_PayPalDirect implements Transaction_Interface_Transaction
{
    /**
     * API test/production URLs and version.
     */
    const API_SANDBOX    = 'https://api-3t.sandbox.paypal.com/nvp';
    const API_PRODUCTION = 'https://api-3t.paypal.com/nvp';
    const API_VERSION    = '89.0';

    /**
     * URL to use for API operations.
     * @var  string
     * @see  __construct() 
     */
    protected $apiUrl = NULL;

    /**
     * Enable/disable address verification service.
     * @var  bool
     */
    protected $enableAvs = TRUE;

    /**
     * The default end-user error message when a response can't be fully parsed.
     * @var  string
     */
    protected $defaultError = 'The transaction could not be processed.';

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * An array containing all of the parameters and values to send to PayPal for the next
     * API operation.
     *
     * @var  array
     */
    protected $params = array();

    /**
     * An array containing the last response from the PayPal API.
     * @var  array
     */
    protected $lastResponse = array();

    /**
     * ID of the last successful transaction, or ID manually set from a previous preauth
     * transaction.
     *
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * Class constructor
     *
     * - Sets PayPal API username.
     * - Sets PayPal API password.
     * - Sets PayPal API Signature.
     * - Enable/disable test mode.
     *
     * @param   string
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct($username, $password, $signature, $testMode = FALSE)
    {
        // Set API credentials
        $this->params['USER']      = $username;
        $this->params['PWD']       = $password;
        $this->params['SIGNATURE'] = $signature;

        // Determine which API URL ot use (based on test/production modes)
        $this->apiUrl = $testMode ? self::API_SANDBOX : self::API_PRODUCTION;
    }

    /**
     * Get human-readable error message.
     * @return  string  NULL if no error found.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set human readable error message.
     * @param   string
     * @return  void
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * See if any errors or problems encountered during API communication.
     * @return  bool  TRUE if no errors found, FALSE otherwise.
     */
    public function isValid()
    {
        return NULL === $this->error;
    }

    /**
     * Get transaction ID generated from last successful transaction.
     * @return  string  NULL if no transaction ID present, string otherwise.
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Manually set transaction ID from previous preauth transaction or equivalent.
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
    }

    /**
     * Set the PayPal partner code (BN code).
     * @param   string
     * @return  void
     */
    public function setPartnerCode($code)
    {
        $this->params['BUTTONSOURCE'] = $code;     
    }

    /**
     * Set the first name on card.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->params['FIRSTNAME'] = trim($firstName);
    }

    /**
     * Set the last name on card.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->params['LASTNAME'] = trim($lastName);
    }

    /**
     * Set the address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->params['STREET'] = trim($address1);
    }

    /**
     * Set the second line of address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        // Second address line not applicable
        if ( ! strlen(trim($address2))) {
            return;
        }

        $this->params['STREET2'] = trim($address2);
    }

    /**
     * Set the city on file for card.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->params['CITY'] = trim($city);
    }

    /**
     * Set the state/province on file for card.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->params['STATE'] = trim($province);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->params['COUNTRYCODE'] = trim($country);
    }

    /**
     * Set the zip/postal code on file for card.
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->params['ZIP'] = trim($code);
    }

    /**
     * Set the card number.
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->params['ACCT'] = preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Set the card's expiration date.
     * @param   string|int  Expiration month. Must be two digits (01-12).
     * @param   string|int  Expiration year. Must be four digits (2014).
     * @return  void
     */
    public function setExpirationDate($month, $year)
    {
        // Clean month
        if (1 === strlen($month)) {
            $month = '0' . $month;
        }

        // Set expiration date (MMYYYY format)
        $this->params['EXPDATE'] = $month . $year;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['CVV2'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->params['CURRENCYCODE'] = trim(strtoupper($currency));
    }

    /**
     * Enable/disable address verification.
     * @param   bool  TRUE when AVS should be ON, FALSE when AVS should be OFF.
     * @return  void
     */
    public function setAddressVerification($avs)
    {
        $this->enableAvs = (bool) $avs;
    }

    /**
     * Sets the email address of buyer.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        $this->params['EMAIL'] = trim($email);
    }

    /**
     * Set a custom paramater to the API.
     * @param   string  The parameter name.
     * @param   string  The parameter value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        $this->params[$param] = $value;
    }

    /**
     * Do a pre-authorization transaction.
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        // Verify address first (if applicable)
        if ($this->enableAvs && ! $this->process('AddressVerify')) {
            return FALSE;
        }

        // Attempt authorization
        $this->params['PAYMENTACTION'] = 'Authorization';
        $this->params['AMT'] = number_format($amount, 2, '.', '');
        return $this->process('DoDirectPayment');
    }

    /**
     * Do a capture on a prior authorization.
     *
     * [!!!] The authorization transaction ID must already be set.
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt capture
        $this->params['AMT']             = number_format($amount, 2, '.', '');
        $this->params['COMPLETETYPE']    = 'Complete';
        $this->params['AUTHORIZATIONID'] = $this->transactionId;
        return $this->process('DoCapture');
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        $this->params['PAYMENTACTION'] = 'Sale';
        $this->params['AMT'] = number_format($amount, 2, '.', '');
        return $this->process('DoDirectPayment');
    }

    /**
     * Do a credit to a previous transaction.
     *
     * [!!!] The authorization transaction ID must already be set.
     *
     * @param   float  The amount to credit.
     * @return  bool   TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt credit
        $this->params['TRANSACTIONID'] = $this->getTransactionId();
        $this->params['REFUNDTYPE']    = 'Partial';
        $this->params['AMT']           = number_format($amount, 2, '.', '');
        $this->params['NOTE']          = 'A partial refund of a previous transaction.';
        return $this->process('RefundTransaction');
    }

    /**
     * Process the API operation as applicable.
     * @param   string  The type of API operation (method).
     * @return  bool    TRUE if transaction successful, FALSE otherwise.
     */
    protected function process($method)
    {
        // Make final data preparations
        $this->params['METHOD'] = $method;
        $this->prepareData();

        // Start with an empty array (will be imploded into string eventually)
        $request = array();

        // Loop through all parameters and compile a URL encoded string for each param
        foreach ($this->params as $param => $value) {
            $request[] = $param . '=' . urlencode($value);
        }

        // Start cURL
        $handle = curl_init();

        // Prepare cURL
        curl_setopt($handle, CURLOPT_VERBOSE, 1);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_URL, $this->apiUrl);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, implode('&', $request));

        // Execute cURL
        $response = curl_exec($handle);

        // Close cURL
        curl_close($handle);

        // Convert response to an array
        $response = explode('&', $response);

        // Reset the last response
        $this->lastResponse = array();

        // We have a valid response array to work with
        if (is_array($response) && ! empty($response)) {
            // Loop through response
            foreach ($response as $item) {
                // Get the response parameter name and value
                $item = explode('=', $item);

                // Valid response data, append to _last_response array
                if (is_array($item) && isset($item[1])) {
                    $this->lastResponse[urldecode($item[0])] = urldecode($item[1]);
                }
            }
        }

        // Result (bool)
        return $this->parseResponse($this->lastResponse);
    }

    /**
     * Parse a PayPal API response.
     * @param   array
     * @return  bool   TRUE if response was positive, FALSE if response was negative.
     */
    protected function parseResponse(array $response)
    {
        // Failed to process transaction
        if (   ! isset($response['ACK'])
            || ! in_array($response['ACK'], array('Success', 'SuccessWithWarning'))
        ) {
            // Use error message provided
            if (isset($response['L_LONGMESSAGE0'])) {
                $this->setError($response['L_LONGMESSAGE0']);
                return FALSE;
            // Use error message provided
            } elseif (isset($response['L_SHORTMESSAGE0'])) {
                $this->setError($response['L_SHORTMESSAGE0']);
                return FALSE;
            // No error messsage provided, so use default error message
            } else {
                $this->setError($this->defaultError);
                return FALSE;
            }
        }

        // AVS was utilized, verify that there were no warnings
        if ('AddressVerify' === $this->params['METHOD']) {
            // AVS failed
            if ('Confirmed' !== $response['CONFIRMATIONCODE']) {
                $this->setError("Address does not match card's address on file.");
                return FALSE;
            }
        // A successful refund
        } elseif ('RefundTransaction' === $this->params['METHOD']) {
            $this->setTransactionId($response['REFUNDTRANSACTIONID']);
        // A successful void of capture (currently not implemented)
        } elseif ('DoVoid' === $this->params['METHOD']) {
            $this->setTransactionId($response['AUTHORIZATIONID']);
        // A successful transaction
        } else {
            $this->setTransactionId($response['TRANSACTIONID']);
        }

        // Transaction successful
        return TRUE;
    }

    /**
     * Make final adjustments to the parameter data before performing an API operation.
     * @return  void
     */
    protected function prepareData()
    {
        // Auto-set card type based on card number
        if (isset($this->params['ACCT']) && ! isset($this->params['CREDITCARDTYPE'])) { 
            // All Visa card numbers start with a 4. New cards have 16 digits. Old cards have 13.
            if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $this->params['ACCT'])) {
                $this->params['CREDITCARDTYPE'] = 'Visa';
            // All MasterCard numbers start with the numbers 51 through 55. All have 16 digits.
            } elseif (preg_match( "/^5[1-5][0-9]{14}$/", $this->params['ACCT'])) {
                $this->params['CREDITCARDTYPE'] = 'MasterCard';
            // American Express card numbers start with 34 or 37 and have 15 digits. 
            } elseif (preg_match( "/^3[47][0-9]{13}$/", $this->params['ACCT'])) {
                $this->params['CREDITCARDTYPE'] = 'Amex';
            //Discover card numbers begin with 6011 or 65. All have 16 digits.
            } elseif (preg_match( "/^6(?:011|5[0-9]{2})[0-9]{12}$/", $this->params['ACCT'])) {
                $this->params['CREDITCARDTYPE'] = 'Discover';
            // Card number format not recognized
            } else {
                $this->setError('The credit card number is invalid.');
            }
        }

        // Autoset IP address
        $this->params['VERSION']   = self::API_VERSION;
        $this->params['IPADDRESS'] = $this->getIpAddress();
    }

    /**
     * Get the end user's current IP address.
     * @return  string
     */
    protected function getIpAddress()
    {
        return Vine_Request::getIp();
    }
}