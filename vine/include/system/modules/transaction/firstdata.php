<?php

// Dependencies
require_once 'first-data/lphp.php';

/**
 * Wrapper class for the First Data Global Gateway API.
 *
 * [!!!] The "First Data Global Gateway" and "First Data Global Gateway E4" are two
 *       completely different APIs.
 *
 * @author  Tell Konkle
 * @date    2012-07-23
 */
class Transaction_FirstData implements Transaction_Interface_Transaction
{
    /**
     * API test/production URLs and port.
     */
    const API_SANDBOX    = 'staging.linkpt.net';
    const API_PRODUCTION = 'secure.linkpt.net';
    const API_PORT       = '1129';

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
     * ID of the last successful transaction, or ID manually set from a previous preauth
     * transaction.
     *
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * Class constructor
     *
     * - Sets FirstData store number.
     * - Sets path to FirstData certificate (PEM) file.
     * - Enable/disable test mode.
     *
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct($storeNumber, $certificatePath, $testMode = FALSE)
    {
        // Set API access info and API URL
        $this->params['configfile'] = $storeNumber;
        $this->params['keyfile']    = $certificatePath;
        $this->params['host']       = self::API_PRODUCTION;
        $this->params['port']       = self::API_PORT;
        $this->params['result']     = 'LIVE';

        /**
         * In test mode, for debugging, you can specify the desired result.
         *
         * @code
         *
         * $gateway->setParam('debugging', TRUE);
         * $gateway->setParam('result', 'DECLINE');
         *
         * @endcode
         *
         * For testing, you should specify a "result" parameter of "GOOD", "DECLINE", or
         * "DUPLICATE." The "LIVE" value is when transactions are being processed in
         * production mode.
         */

        // Enable test mode
        if ($testMode) {
            $this->params['host']   = self::API_SANDBOX;
            $this->params['result'] = 'GOOD';
        }
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
     * Manually set transaction ID from previous preauth transaction or equivalent.
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
    }

    /**
     * Set the first name on card.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);
    }

    /**
     * Set the last name on card.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);
    }

    /**
     * Set the address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->params['address1'] = trim($address1);
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

        $this->params['address2'] = trim($address2);
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
        $this->params['zip'] = trim($code);
    }

    /**
     * Set the card number.
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->params['cardnumber'] = preg_replace('/[^0-9]/', '', $number);
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

        // Set card's expiration date
        $this->params['cardexpmonth'] = $month;
        $this->params['cardexpyear']  = $year;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['cvmvalue'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        return; // Currency code not supported by this gateway
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
        $this->params['ordertype']   = 'PREAUTH';
        $this->params['chargetotal'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
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
        $this->_params['ordertype']   = 'POSTAUTH';
        $this->_params['chargetotal'] = number_format((float) $amount, 2, '.', '');
        $this->_params['oid']         = $this->transactionId;
        return $this->process();
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        $this->params['ordertype']   = 'SALE';
        $this->params['chargetotal'] = number_format((float) $amount, 2, '.', '');
        return $this->process();
    }

    /**
     * Do a credit to a previous transaction.
     *
     * [!!!] The transaction ID must already be set.
     * [!!!] The card number must already be set.
     *
     * @param   string|float  The credit amount. Not all API's support amount.
     * @return  bool          TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setCardNumber()
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit on a prior capture
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Card number required to do a credit on a prior capture
        if ( ! isset($this->params['cardnumber'])) {
            $this->setError('Unable to complete credit. Missing card number.');
            return FALSE;
        }

        // Attempt credit
        $this->params['ordertype']   = 'CREDIT';
        $this->params['chargetotal'] = number_format((float) $amount, 2, '.', '');
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

        // Use FirstData LinkPoint PHP library to process transaction
        $lp = new lphp();
        $this->lastResponse = $lp->curl_process($this->params);

        // Parse response
        if (is_array($this->lastResponse)) {
            return $this->parseResponse($this->lastResponse);
        }

        // Transaction failed
        $this->setError($this->defaultError);
        return FALSE;
    }

    /**
     * Parse a FirstData API response.
     * @param   array
     * @return  bool   TRUE if response was positive, FALSE if response was negative.
     */
    protected function parseResponse(array $response)
    {
        // Transaction successful
        if ('APPROVED' === $response['r_approved']) {
            $this->transactionId = $response['r_ordernum'];
            return TRUE;
        }

        // Human-readable error message
        $this->setError('Transaction failed. ' . $response['r_approved'] . ': '
                      . $response['r_error'] . '.');

        // Transaction failed
        return FALSE;
    }

    /**
     * Make final adjustments to the parameter data before performing an API operation.
     * @return  void
     */
    protected function prepareData()
    {
        // User's IP address
        $this->params['ip'] = $this->getIpAddress();

        // Card's security code has been (or should be, hehe) provided
        $this->params['cvmindicator'] = 'provided';

        // Required for AVS
        if ($this->enableAvs && isset($this->params['address1'])) {
            $this->params['addrnum'] = preg_replace('/[^0-9]/', '', $this->params['address1']);
        }

        // Set first name on card
        if (isset($this->firstName)) {
            $this->params['name'] = $this->firstName;
        }

        // Append last name to first name if provided
        if (isset($this->lastName)) {
            $this->params['name'] .= ' ' . $this->lastName;
        }

        // Trim name in case only last name was provided (so there's no whitespace)
        if (isset($this->params['name'])) {
            $this->params['name'] = trim($this->params['name']);
        }
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