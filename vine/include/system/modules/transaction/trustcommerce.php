<?php

/**
 * Wrapper class for the Trust Commerce API.
 *
 * @author  Tell Konkle
 * @date    2015-11-19
 */
class Transaction_TrustCommerce
implements Transaction_Interface_Transaction, Transaction_Interface_Billing
{
    /**
     * The URL to the API when sending via cURL.
     */
    const API_URL = 'https://vault.trustcommerce.com/trans/';

    /**
     * An array containing the error messages to display for the various decline types.
     * The array format is: 'declinetype' => 'error message'
     *
     * @var  array
     */
    protected $declineErrors = array
    (
        'decline'      => 'Card declined because of insufficient funds.',
        'avs'          => 'Card declined because the billing address provided does not match the card on file.',
        'cvv'          => 'Card declined because the security code is invalid.',
        'call'         => 'Card declined because the card must be authorized manually over the phone.',
        'expiredcard'  => 'Card declined because it has expired.',
        'carderror'    => 'Card declined because the card number is invalid.',
        'authexpired'  => 'Card declined because the authorization on this card has expired.',
        'fraud'        => 'Card declined because of possible fraud.',
        'blacklist'    => 'Card declined because of possible fraud.',
        'velocity'     => 'Card declined because of velocity control limits.',
        'dailylimit'   => 'Card declined because the daily limit on the card or merchant has been reached.',
        'weeklylimit'  => 'Card declined because the weekly limit on the card or merchant has been reached.',
        'monthlylimit' => 'Card declined because the monthly limit on the card or merchant has been reached.',
    );

    /**
     * The default end-user error message when a response can't be fully parsed.
     * @var  string
     */
    protected $defaultError = 'The transaction could not be processed.';

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
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * An array containing all of the parameters and values to send to TrustCommerce for
     * the next API operation.
     *
     * @var  array
     */
    protected $params = array();

    /**
     * An array containing the last response from the TrustCommerce API.
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
     * If TRUE, the TCLink PHP extension will be used to send API transactions to
     * TrustCommerce. If FALSE, cURL will be used instead.
     *
     * @var  bool
     * @see  __constuct()
     */
    protected $useExtension = FALSE;

    /**
     * Class constructor. Determines whether transaction will be sent using cURL or using
     * the TCLink PHP extension.
     *
     * - Sets TrustCommerce customer ID.
     * - Sets TrustCommerce password.
     * - Enable/disable test mode.
     *
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct($customerId, $password, $testMode = FALSE)
    {
        try {
            // The TCLink PHP extension is loaded properly
            if (extension_loaded('tclink') && function_exists('tclink_send')) {
                $this->useExtension = TRUE;
            // The TCLink PHP extension was not found, use cURL instead
            } elseif (function_exists('curl_init')) {
                $this->useExtension = FALSE;
            // No valid extensions found
            } else {
                throw new VineBadObjectException('Requires TCLink or cURL extension.');
            }

            // Set API credentials and test mode status
            $this->params['custid']   = $customerId;
            $this->params['password'] = $password;
            $this->params['demo']     = TRUE === $testMode ? 'y' : 'n';
        } catch (VineBadObjectException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Get the last response from the API.
     * @return  array
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
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
        // Error code; convert to error message
        if (isset($this->declineErrors[$error])) {
            $this->error = $this->declineErrors[$error];
        // Error message
        } else {
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
        $this->params['cc'] = preg_replace('/[^0-9]/', '', $number);
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

        // Clean year
        if (4 === strlen($year)) {
            $year = substr($year, -2);
        }

        // Set expiration date (MMYY format)
        $this->params['exp'] = $month . $year;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['cvv'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->params['currency'] = trim(strtolower($currency));
    }

    /**
     * Enable/disable address verification.
     * @param   bool  TRUE when AVS should be ON, FALSE when AVS should be OFF.
     * @return  void
     */
    public function setAddressVerification($avs)
    {
        $this->params['avs'] = FALSE === $avs ? 'n' : 'y';
    }

    /**
     * Sets the fraud score threshold. Requires a TrustCommerce account with CrediGuard
     * enabled. Transactions with a score that is below the set threshold will be
     * rejected. The following are the typical scoring threshold levels:
     *
     * 0        Allow All
     * 1 - 25   Allow Most
     * 26 - 50  Normal
     * 51 - 75  Restrictive
     * 76 - 100 Highly Restrictive
     *
     * @param   int   The minimum CrediGuard score required for transaction approval.
     * @return  void
     */
    public function setFraudScore($threshold)
    {
        $this->params['threshold'] = (int) $threshold;
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
     * Unset a parameter from being sent to the API.
     * @param   string  The parameter name.
     * @return  void
     */
    public function unsetParam($param)
    {
        unset($this->params[$param]);
    }

    /**
     * Do a pre-authorization transaction.
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        unset($this->params['billingid']);
        $this->params['amount'] = number_format((float) $amount, 2, '', '');
        return $this->process('preauth');
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
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt capture
        unset($this->params['billingid']);
        $this->params['amount']  = number_format((float) $amount, 2, '', '');
        $this->params['transid'] = $this->transactionId;
        return $this->process('postauth');
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        unset($this->params['billingid']);
        $this->params['amount'] = number_format((float) $amount, 2, '', '');
        return $this->process('sale');
    }

    /**
     * Do a credit to a previous transaction.
     *
     * [!!!] The authorization transaction ID must already be set.
     *
     * @param   string|int|float  The credit amount.
     * @return  bool              TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit on a prior transaction
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt credit
        unset($this->params['billingid']);
        $this->params['amount']  = number_format((float) $amount, 2, '', '');
        $this->params['transid'] = $this->transactionId;
        return $this->process('credit');
    }

    /**
     * Get the Citadel billing ID.
     * @return  string|int  NULL if no billing ID present.
     */
    public function getBillingId()
    {
        return isset($this->params['billingid']) ? $this->params['billingid'] : NULL;
    }

    /**
     * Set a Citadel billing ID.
     * @param   string|int
     * @return  void
     */
    public function setBillingId($billingId)
    {
        $this->params['billingid'] = trim($billingId);
    }

    /**
     * Create a Citadel billing ID.
     * @return  bool  TRUE if billing ID created successfully, FALSE otherwise.
     */
    public function doBillingCreate($verify = 'y')
    {
        $this->params['verify'] = $verify;
        return $this->process('store');
    }

    /**
     * Delete a Citadel billing ID.
     *
     * [!!!] The Citadel billing ID must already be set with setBillingId().
     *
     * @return  bool  TRUE if billing ID deleted successfully, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingDelete()
    {
        // Billing ID is required to do a billing delete
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to delete billing profile. Billing ID not loaded.');
            return FALSE;
        }

        // Attempt billing delete
        return $this->process('unstore');
    }

    /**
     * Update a Citadel billing ID.
     *
     * [!!!] The Citadel billing ID must already be set with setBillingId().
     *
     * @return  bool  TRUE if billing ID updated successfully, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingUpdate()
    {
        // Billing ID is required to do billing update
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete billing update. Missing billing ID.');
            return FALSE;
        }

        // Attempt billing update
        $this->params['verify'] = 'y';
        return $this->process('store');
    }

    /**
     * Do a pre-authorization transaction using a Citadel billing ID.
     *
     * [!!!] The Citadel billing ID must already be set with setBillingId().
     *
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingPreAuth($amount)
    {
        // Billing ID is required to do an authorization
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete authorization. Missing billing ID.');
            return FALSE;
        }

        // Attempt authorization
        $this->params['amount'] = number_format((float) $amount, 2, '', '');
        return $this->process('preauth');
    }

    /**
     * Do a capture against an authorization that was made using a Citadel billing ID.
     *
     * [!!!] The authorization transaction ID must already be set.
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doBillingPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt capture
        $this->params['amount']  = number_format((float) $amount, 2, '', '');
        $this->params['transid'] = $this->transactionId;
        return $this->process('postauth');
    }

    /**
     * Do an authorization and capture (sale) using a Citadel billing ID.
     *
     * [!!!] The Citadel billing ID must already be set with setBillingId().
     *
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingSale($amount)
    {
        // Billing ID is required to do a sale
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete sale. Missing billing ID.');
            return FALSE;
        }

        // Attempt sale
        $this->params['amount'] = number_format((float) $amount, 2, '', '');
        return $this->process('sale');
    }

    /**
     * Do a credit to a previous transaction using a Citadel billing ID.
     *
     * [!!!] The authorization transaction ID must already be set.
     *
     * @param   string|int|float  The credit amount.
     * @return  bool              TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     */
    public function doBillingCredit($amount)
    {
        // Transaction ID is required to do a credit on a prior transaction
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Attempt credit
        $this->params['amount']  = number_format((float) $amount, 2, '', '');
        $this->params['transid'] = $this->transactionId;
        return $this->process('credit');
    }

    /**
     * Process the API operation as applicable.
     * @param   string  The type of API operation (action). Must be a valid TC action.
     * @return  bool    TRUE if transaction successful, FALSE otherwise.
     */
    protected function process($action)
    {
        // Set the action
        $this->params['action'] = strtolower($action);

        // Finalize the data to be sent
        $this->prepareData();

        // Process using the TCLink PHP Extension
        if ($this->useExtension) {
            return $this->sendUsingExtension();
        // Process using cURL
        } else {
            return $this->sendUsingCurl();
        }
    }

    /**
     * Processes the transaction using the TCLink PHP extension.
     * @return  bool  TRUE if transaction successful, FALSE otherwise.
     */
    protected function sendUsingExtension()
    {
        // Send using TCLink PHP Extension
        $this->lastResponse = tclink_send($this->params);

        // Result (bool)
        return $this->parseResponse($this->lastResponse);
    }

    /**
     * Processes the transaction using cURL.
     * @return  bool  TRUE if transaction successful, FALSE otherwise.
     */
    protected function sendUsingCurl()
    {
        // Start with an empty array
        $request = array();

        // Loop all parameters
        foreach ($this->params as $param => $value) {
            $request[] = $param . '=' . urlencode($value);
        }

        // Start cURL
        $handle = curl_init();

        // Prepare cURL
        curl_setopt($handle, CURLOPT_URL, self::API_URL);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, implode('&', $request));

        // Execute cURL
        $response = curl_exec($handle);

        // Close cURL
        curl_close($handle);

        // Convert response to an array (each line is a response parameter)
        $response = explode("\n", $response);

        // Reset the last response
        $this->lastResponse = array();

        // Valid response array
        if (is_array($response) && ! empty($response)) {
            // Loop through response
            foreach ($response as $item) {
                // Get the response parameter name and value
                $item = explode('=', $item);

                // Valid response parameter, append to array
                if (is_array($item) && isset($item[1])) {
                    $this->lastResponse[$item[0]] = $item[1];
                }
            }
        }

        // Result (bool)
        return $this->parseResponse($this->lastResponse);
    }

    /**
     * Parse a TrustCommerce API response.
     * @param   array
     * @return  bool   TRUE if response was positive, FALSE otherwise.
     */
    protected function parseResponse(array $response)
    {
        // Not a valid response
        if ( ! isset($response['status'])) {
            $this->setError($this->defaultError);
            return FALSE;
        }

        // Reset billing ID if applicable
        if ( ! $this->_isUpdateBilling() && ! $this->_isDeleteBilling()) {
            unset($this->params['billingid']);
        }

        // Save new transaction ID or reset
        $this->transactionId = isset($response['transid']) ? $response['transid'] : NULL;

        // Save billing ID if provided
        if (isset($response['billingid'])) {
            $this->params['billingid'] = $response['billingid'];
        }

        // Successful
        if ('approved' === $response['status']) {
            return TRUE;
        // Successful
        } elseif ('accepted' === $response['status']) {
            return TRUE;
        }

        // Failed, transaction declined
        if ('decline' === $response['status']) {
            $this->setError($response['declinetype']);
            return FALSE;
        // Failed, fields likely missing
        } elseif (isset($response['error'])) {
            $this->setError($response['error']);
            return FALSE;
        // Failed, not sure why
        } else {
            $this->setError($this->defaultError);
            return FALSE;
        }
    }

    /**
     * Make final adjustments to the parameter data before performing an API operation.
     * @return  void
     */
    protected function prepareData()
    {
        // Combine $firstName and $lastName variables (needed for interface complicance)
        if (isset($this->firstName) && isset($this->lastName)) {
            $this->params['name'] = $this->firstName . ' ' . $this->lastName;
        }

        // Set IP address when it's needed and convenient
        if ( ! in_array($this->params['action'], array('credit', 'postauth', 'unstore'))) {
            $this->params['ip'] = $this->getIpAddress();
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

    /**
     * See whether or not the current/last transaction is an billing update.
     * @return  bool
     */
    private function _isUpdateBilling()
    {
        if ( ! isset($this->params['action'])) {
            return FALSE;
        }

        return 'store' === $this->params['action'] && isset($this->params['billingid']);
    }

    /**
     * See whether or not the current/last transaction is a billing cancellation.
     * @return  bool
     */
    private function _isDeleteBilling()
    {
        if ( ! isset( $this->params['action'])) {
            return FALSE;
        }

        return 'unstore' === $this->params['action'] && isset($this->params['billingid']);
    }
}