<?php

/**
 * Wrapper class for the First Data Global Gateway E4 API.
 *
 * [!!!] The "First Data Global Gateway" and "First Data Global Gateway E4" are two
 *       completely different APIs.
 *
 * Test Credit Card Numbers:
 *
 * Visa               4111111111111111    Expiry Date: Any future date.
 * Mastercard         5500000000000004    Expiry Date: Any future date.
 * American Express   340000000000009     Expiry Date: Any future date.
 * JCB                3566002020140006    Expiry Date: Any future date.
 * Discover           6011000000000004    Expiry Date: Any future date.
 * Diners Club        36438999960016      Expiry Date: Any future date.
 *
 * @author  Tell Konkle
 * @date    2014-04-08
 * @see     https://firstdata.zendesk.com/home
 */
class Transaction_FirstDataE4 implements Transaction_Interface_Transaction
{
    /**
     * URL to the E4 API. 
     */
    const URL_LIVE = 'https://api.globalgatewaye4.firstdata.com/transaction/v11';
    const URL_TEST = 'https://api.demo.globalgatewaye4.firstdata.com/transaction/v11';

    /**
     * Transaction type codes. 
     */
    const CODE_SALE     = '00';
    const CODE_PREAUTH  = '01';
    const CODE_POSTAUTH = '32';
    const CODE_CREDIT   = '34';

    /**
     * The first name on the credit card.
     * @var  string
     * @see  setFirstName()
     */
    protected $firstName = NULL;

    /**
     * The last name on the credit card.
     * @var  string
     * @see  setLastName()
     */
    protected $lastName = NULL;

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
     * An array containing all of the parameters and values to send in the next API
     * operation.
     *
     * @var  array
     */
    protected $params = array();

    /**
     * An array containing the last response from the FirstData API.
     * @var  array
     */
    protected $lastResponse = array();

    /**
     * ID of the last successful transaction, or ID manually set from a previous
     * transaction.
     *
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * Reference ID of the last successful transaction, or ID manually set from a previous
     * transaction.
     *
     * @var  mixed
     */
    protected $referenceId = NULL;

    /**
     * Enable/disable test mode.
     * @var  bool
     * @see  __construct()
     */
    protected $testMode = FALSE;

    /**
     * Class constructor
     *
     * - Sets E4 gateway ID (exact ID).
     * - Sets E4 API password.
     * - Sets test mode.
     *
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct($gatewayId, $password, $testMode = FALSE)
    {
	    $this->params['gateway_id'] = $gatewayId;
	    $this->params['password']   = $password;
        $this->testMode             = (bool) $testMode;
    }

    /**
     * Class destructor.
     * @return  void
     */
    public function __destruct()
    {
        // Only log when in test mode
        if ( ! $this->testMode) {
            return;
        }

        // Hide credit card number
        if (isset($this->params['cc_number'])) {
            $this->params['cc_number'] = '****';
        }

        // Hide expiration date
        if (isset($this->params['cc_expiry'])) {
            $this->params['cc_expiry'] = '****';
        }

        // Hide security code
        if (isset($this->params['cc_verification_str2'])) {
            $this->params['cc_verification_str2'] = '****';
        }

        // Hide expiration date from response
        if (isset($this->lastResponse['cc_expiry'])) {
            $this->lastResponse['cc_expiry'] = '****';
        }

        // Hide security code from response
        if (isset($this->lastResponse['cc_verification_str2'])) {
            $this->lastResponse['cc_verification_str2'] = '****';
        }

        // Compile log data
        $data = "REQUEST:\n\n"
              . print_r($this->params, TRUE) . "\n"
              . "RESPONSE:\n\n"
              . print_r($this->lastResponse, TRUE);

        // Put the log into the log directory
        Vine_Log::logManual($data, 'firstdata-e4.log');
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
        if (strlen($error)) {
            $this->error = $error;
        }
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
     * Get reference ID generated from last successful transaction.
     * @return  string  NULL if no reference ID present, string otherwise.
     */
    public function getReferenceId()
    {
        return $this->referenceId;
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
     * Manually set reference ID from a previous transaction.
     * @param   string
     * @return  void
     */
    public function setReferenceId($id)
    {
        $this->referenceId = $id;
    }

    /**
     * Set the first name on card.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Set the last name on card.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Set the address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->params['address_1'] = trim($address1);
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

        $this->params['address_2'] = trim($address2);
    }

    /**
     * Set the city on file for card.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->params['city'] = trim($city);
    }

    /**
     * Set the state/province on file for card.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->params['state'] = trim($province);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->params['country'] = trim($country);
    }

    /**
     * Set the zip/postal code on file for card.
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->params['zip_code'] = trim($code);
    }

    /**
     * Set the card number.
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->params['cc_number'] = preg_replace('/[^0-9]/', '', $number);
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

        // Clean year (get last 2 digits of a YYYY format)
        $year = substr($year , (strlen($year) - 2), 2);

        // Set expiration date in MMYY format
        $this->params['cc_expiry'] = $month . $year;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['cc_verification_str2'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->params['currency_code'] = trim(strtoupper($currency));
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
        $this->params['client_email'] = trim($email);
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
        $this->params['transaction_type'] = self::CODE_PREAUTH;
        $this->params['amount']           = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do a capture on a prior authorization.
     *
     * [!!!] The transaction ID must already be set.
     * [!!!] The reference ID must already be set.
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setReferenceId()
     */
    public function doPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Reference ID is required to do a capture on a prior authorization
        if (NULL === $this->referenceId) {
            $this->setError('Unable to complete capture. Missing Reference ID.');
            return FALSE;
        }

        // Attempt capture
        $this->params['transaction_type']  = self::CODE_POSTAUTH;
        $this->params['authorization_num'] = $this->transactionId;
        $this->params['transaction_tag']   = $this->referenceId;
        $this->params['amount']      = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        $this->params['transaction_type'] = self::CODE_SALE;
        $this->params['amount']     = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do a credit to a previous transaction.
     *
     * [!!!] The transaction ID must already be set.
     * [!!!] The reference ID must already be set.
     *
     * @param   string|float  The credit amount. Not all API's support amount.
     * @return  bool          TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setReferenceId()
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Reference ID is required to do a credit
        if (NULL === $this->referenceId) {
            $this->setError('Unable to complete credit. Missing Reference ID.');
            return FALSE;
        }

        // Attempt credit
        $this->params['transaction_type']  = self::CODE_CREDIT;
        $this->params['authorization_num'] = $this->transactionId;
        $this->params['transaction_tag']   = $this->referenceId;
        $this->params['amount']            = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Process the API operation as applicable.
     * @return  bool    TRUE if transaction successful, FALSE otherwise.
     */
    protected function process()
    {
        // Prepare data before running API operation
        $this->prepareData();

        // Encode data in JSON format before sending to API
        $request = Vine_Json::encode($this->params);

        // Prepare headers
        $headers = array
        (
            'Content-Type: application/json; charset=UTF-8;',
            'Accept: application/json'
        );

        // Initializing curl
        $handle = curl_init($this->testMode ? self::URL_TEST : self::URL_LIVE);
        curl_setopt($handle, CURLOPT_HEADER, FALSE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        // Execute curl
        $result = curl_exec($handle);

        // Request failed completely, stop here
        if (FALSE === $result) {
            $this->lastResponse = FALSE;
            $this->setError($this->defaultError);
            return FALSE;
        }

        // Save the last response
        $this->lastResponse = Vine_Json::decode($result, TRUE);

        // Something else went wrong
        if (NULL === $this->lastResponse) {
            $this->setError($result);
            return FALSE;
        }

        // (bool) parse response
        return $this->parseResponse($this->lastResponse);
    }

    /**
     * Parse a E4 API response.
     * @param   array
     * @return  bool   TRUE if response was positive, FALSE if response was negative.
     */
    protected function parseResponse(array $response)
    {
        // (bool)
        $status = filter_var($response['transaction_approved'], FILTER_VALIDATE_BOOLEAN);

        // Failed
        if ( ! $status) {
            // System error
            if (isset($response['error_number'])
                && (bool) $response['error_number']
            ) {
                $this->setError(
                    $response['error_description'] . '. Code: ' .
                    $response['error_number'] . '.'
                );
            // Transaction failed, compile clean error message
            } elseif (isset($response['transaction_error'])
                && (bool) $response['transaction_error']
            ) {
                $message = ucfirst(strtolower(trim($response['exact_message'], '.')));
                $this->setError('Transaction failed. ' . $message . '.');
            // Couldn't parse reason for fail, use default error message
            } else {
                $this->setError($this->defaultError);
            }

            // Transaction failed, stop here
            return FALSE;
        }

        // Response successful, save transaction ID and reference ID
        $this->transactionId = $response['authorization_num'];
        $this->referenceId   = $response['transaction_tag'];
        return TRUE;
    }

    /**
     * Make final adjustments to the parameter data before performing an API operation.
     * @return  void
     */
    protected function prepareData()
    {
        // Parameters that must be present for the transaction to be successful
        $this->params['cardholder_name']      = $this->firstName . ' ' . $this->lastName;
        $this->params['cc_number']            = $this->getParam('cc_number');
        $this->params['cc_expiry']            = $this->getParam('cc_expiry');
        $this->params['zip_code']             = $this->getParam('zip_code');
        $this->params['surcharge_amount']     = $this->getParam('surcharge_amount');
        $this->params['transaction_tag']      = $this->getParam('transaction_tag');
        $this->params['track1']               = $this->getParam('track1');
        $this->params['track2']               = $this->getParam('track2');
        $this->params['pan']                  = $this->getParam('pan');
        $this->params['authorization_num']    = $this->getParam('authorization_num');
        $this->params['cc_verification_str1'] = $this->getParam('cc_verification_str1');
        $this->params['cc_verification_str2'] = $this->getParam('cc_verification_str2');
        $this->params['cvd_presence_ind']     = $this->getParam('cvd_presence_ind');
        $this->params['tax1_amount']          = $this->getParam('tax1_amount');
        $this->params['tax1_number']          = $this->getParam('tax1_number');
        $this->params['tax2_amount']          = $this->getParam('tax2_amount');
        $this->params['tax2_number']          = $this->getParam('tax2_number');
        $this->params['secure_auth_required'] = $this->getParam('secure_auth_required');
        $this->params['secure_auth_result']   = $this->getParam('secure_auth_result');
        $this->params['ecommerce_flag']       = $this->getParam('ecommerce_flag');
        $this->params['xid']                  = $this->getParam('xid');
        $this->params['cavv']                 = $this->getParam('cavv');
        $this->params['cavv_algorithm']       = $this->getParam('cavv_algorithm');
        $this->params['reference_no']         = $this->getParam('reference_no');
        $this->params['customer_ref']         = $this->getParam('customer_ref');
        $this->params['reference_3']          = $this->getParam('reference_3');
        $this->params['language']             = $this->getParam('language');
        $this->params['client_email']         = $this->getParam('client_email');
        $this->params['user_name']            = $this->getParam('user_name');
        $this->params['currency_code']        = $this->getParam('currency_code');
        $this->params['partial_redemption']   = $this->getParam('partial_redemption');
        $this->params['client_ip']            = $this->getIpAddress();

        // Required for AVS
        if ($this->enableAvs && isset($this->params['address_1'])) {
            // Begin compiling AVS address
            $addy = array();

            // Compile address line 1
            if (isset($this->params['address_1'])) {
                $addy[] = str_replace('|', '', $this->params['address_1']);
            }

            // Compile postal code
            if (isset($this->params['zip_code'])) {
                $addy[] = str_replace('|', '', substr($this->params['zip_code'], 0, 10));
            }

            // Compile city
            if (isset($this->params['city'])) {
                $addy[] = str_replace('|', '', $this->params['city']);
            }

            // Compile province
            if (isset($this->params['state'])) {
                $addy[] = str_replace('|', '', $this->params['state']);
            }

            // Compile country
            if (isset($this->params['country'])) {
                $addy[] = str_replace('|', '', $this->params['country']);
            }

            // Finalize AVS string
            $avs = substr(Vine_Unicode::toAscii(implode('|', $addy)), 0, 41);

            // Final params
            $this->params['cvd_presence_ind']     = '1';
            $this->params['cc_verification_str1'] = $avs;
        }
    }

    /**
     * Get a set parameter value. If parameter has not been set, get empty string.
     * @return  string|mixed
     */
    protected function getParam($param)
    {
        if ( ! isset($this->params[$param])) {
            return '';
        }

        return $this->params[$param];
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