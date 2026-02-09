<?php

/**
 * Wrapper class for the PayPal Express Checkout system.
 *
 * @author  Tell Konkle
 * @date    2013-04-25
 */
class Transaction_PayPalExpress
{
    /**
     * API test/production URLs and version.
     */
    const API_SANDBOX    = 'https://api-3t.sandbox.paypal.com/nvp';
    const API_PRODUCTION = 'https://api-3t.paypal.com/nvp';
    const API_VERSION    = '89.0';

    /**
     * Test/production buyer redirect URLs. 
     */
    const REDIRECT_SANDBOX    = 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';
    const REDIRECT_PRODUCTION = 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=';

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * Get checkout details (usually used for auto-populating form data).
     * @var  array
     * @see  getPayPalData()
     * @see  getData();
     */
    protected $data = array();

    /**
     * Increments +1 for each order item added to checkout description.
     * @var  int
     * @see  setOrderItem()
     */
    protected $itemCount = 0;

    /**
     * The last API response.
     * @var  string
     */
    protected $lastResponse = NULL;

    /**
     * Log of entire cart process. For debugging. Each key in array represents a new line.
     * @var  array
     * @see  log()
     * @see  debug()
     */
    protected $logs = array();

    /**
     * API Name Value Parameters (NVP).
     * @var  array
     */
    protected $params = array();

    /**
     * Test mode?
     * @var  bool
     * @see  setTestMode()
     */
    protected $testMode = FALSE;

    /**
     * The transaction ID from a successful doExpressCheckoutPayment() operation.
     * @var  string
     * @see  doExpressCheckoutPayment()
     * @see  getTransactionId()
     */
    protected $transactionId = NULL;

    /**
     * Remove authorization token and payer data from session, if it exists.
     * @return  void 
     */
    public static function clearAuthorization()
    {
        unset($_SESSION['PAYPAL-TOKEN']);
        unset($_SESSION['PAYPAL-PAYERID']);
    }

    /**
     * Debug the PayPal Express Checkout wrapper.
     *
     * [!!!] If $byIp param is boolean FALSE, debug will proceed.
     *       If $byIp param is boolean TRUE, debug will proceed if IP = registry test-ip.
     *       If $byIp param is IP address, debug will proceed if current IP = IP address.
     *
     * @param   bool|string  Debug based on IP.
     * @param   bool         Display debugging info (if able to debug)?
     * @param   bool         Log debugging info (if able to debug)?
     * @return  void
     */
    public function debug($byIp = TRUE, $display = TRUE, $log = TRUE)
    {
        // Don't debug
        if (   $byIp
            && Vine_Request::getIp() != Vine_Registry::getSetting('test-ip')
            && Vine_Request::getIp() != $byIp
        ) {
            return;
        }

        // Compile debugging info
        $info  = "PAYPAL EXPRESS CHECKOUT - ";
        $info .= $this->testMode ? "TEST MODE\n\n" : "PRODUCTION MODE\n\n";
        $info .= "CHECKOUT LOG:\n\n";
        $info .= implode("\n", $this->logs);
        $info .= "\n\nPARAMETERS:\n\n" . print_r($this->params, TRUE);
        $info .= "\n\nLAST RESPONSE:\n\n" . print_r($this->lastResponse, TRUE);

        // Display debugging info
        if ($display) {
            Vine_Debug::dump($info);
        }

        // Log debugging info
        if ($log) {
            Vine_Log::logDebug($info);
        }
    }

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
     * Set the API Username.
     * @param   string
     * @return  void
     */
    public function setUsername($apiUsername)
    {
        $this->params['USER'] = $apiUsername;
        $this->log('Set param USER: {hidden-for-security}.');
    }

    /**
     * Set the API Password.
     * @param   string
     * @return  void
     */
    public function setPassword($apiPassword)
    {
        $this->params['PWD'] = $apiPassword;
        $this->log('Set param PWD: {hidden-for-security}.');
    }

    /**
     * Set the API Signature.
     * @param   string
     * @return  void
     */
    public function setSignature($apiSignature)
    {
        $this->params['SIGNATURE'] = $apiSignature;
        $this->log('Set param SIGNATURE: {hidden-for-security}.');
    }

    /**
     * Set the PayPal partner code (BN code).
     * @param   string
     * @return  void
     */
    public function setPartnerCode($code)
    {
        $this->params['BUTTONSOURCE'] = $code;
        $this->log('Set param BUTTONSOURCE: ' . $code . '.');        
    }

    /**
     * Set the amount for the transaction.
     *
     * [!!!] When preparing to run the setExpressCheckout() method, this amount does NOT
     *       have to be exact, because taxes and shipping costs may not be determined
     *       until user is redirected back from PayPal to the specified return URL.
     *
     * [!!!] When preparing to run the doExpressCheckoutPayment() method, this amount
     *       should be the the order's exact total.
     *
     * @param   string  Transaction amount in 000.00 format.
     * @param   string  The 3-letter currency code.
     * @return  void
     */
    public function setAmount($amount, $currency = 'USD')
    {
        // Sanitize the request amount
        $amount = preg_replace("/[^0-9\.]/", '', $amount);
        $amount = number_format($amount, 2, '.', '');

        // Save parameters
        $this->params['PAYMENTREQUEST_0_AMT']           = $amount;
        $this->params['PAYMENTREQUEST_0_CURRENCYCODE']  = $currency;
        $this->params['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';

        // For debugging
        $this->log('Set param PAYMENTREQUEST_0_AMT: ' . $amount . '.');
        $this->log('Set param PAYMENTREQUEST_0_CURRENCYCODE: ' . $currency . '.');
        $this->log('Set param PAYMENTREQUEST_0_PAYMENTACTION: Sale.');
    }

    /**
     * Set the shipping & handling cost for order.
     * @param   string  S&H amount in 000.00 format.
     * @return  void 
     */
    public function setShippingAmount($amount)
    {
        // Sanitize the amount
        $amount = preg_replace("/[^0-9\.]/", '', $amount);
        $amount = number_format($amount, 2, '.', '');
        
        // Save amount
        $this->log('Set param PAYMENTREQUEST_0_SHIPPINGAMT: ' . $amount);
        $this->params['PAYMENTREQUEST_0_SHIPPINGAMT'] = $amount;
    }

    /**
     * Set the sum of all taxable items in order.
     * @param   string  Tax amount in 000.00 format.
     * @return  void 
     */
    public function setTaxAmount($amount)
    {
        // Sanitize the amount
        $amount = preg_replace("/[^0-9\.]/", '', $amount);
        $amount = number_format($amount, 2, '.', '');
        
        // Save amount
        $this->log('Set param PAYMENTREQUEST_0_TAXAMT: ' . $amount);
        $this->params['PAYMENTREQUEST_0_TAXAMT'] = $amount;
    }

    /**
     * Items in order. Used to describe order to PayPal when obtaining an authorization
     * token, prior to redirecting buyer to PayPal login.
     *
     * @param  string  The item's name.
     * @param  mixed   The item's unit price, in 000.00 format.
     * @param  int     The order quantity for the item.
     */
    public function setOrderItem($itemName, $price, $qty)
    {
        // Sanitize the price
        $price = preg_replace("/[^0-9\.\-]/", '', $price);
        $price = number_format($price, 2, '.', '');

        // Set applicable params
        $this->params['L_PAYMENTREQUEST_0_NAME' . $this->itemCount] = $itemName;
        $this->params['L_PAYMENTREQUEST_0_AMT' . $this->itemCount]  = $price;
        $this->params['L_PAYMENTREQUEST_0_QTY' . $this->itemCount]  = $qty;

        // For debugging
        $this->log('Set param L_PAYMENTREQUEST_0_NAME{x}: ' . $itemName . '.');
        $this->log('Set param L_PAYMENTREQUEST_0_AMT{x}: ' . $price . '.');
        $this->log('Set param L_PAYMENTREQUEST_0_QTY{x}: ' . $qty . '.');

        // Begin calculating order subtotal
        if ( ! isset($this->params['PAYMENTREQUEST_0_ITEMAMT'])) {
            $this->params['PAYMENTREQUEST_0_ITEMAMT'] = 0.00;
        }

        // Add item price to order subtotal
        $this->params['PAYMENTREQUEST_0_ITEMAMT'] += $price;

        // Increment item count
        $this->itemCount++;
    }

    /**
     * Set the return URL. This is the page which PayPal redirects the buyer to after the
     * buyer logs into PayPal and approves the payment.
     *
     * @param   string  A valid return URL, HTTPS protocol preferred.
     * @return  void
     */
    public function setReturnUrl($url)
    {
        $this->params['RETURNURL'] = $url;
        $this->log('Set param RETURNURL: ' . $url . '.');
    }

    /**
     * Set the cancel URL. This is the page which PayPal redirects the buyer to if the
     * buyer does not approve the payment.
     *
     * @param   string  A valid cancel return URL, HTTPS protocol preferred.
     * @return  void
     */
    public function setCancelUrl($url)
    {
        $this->params['CANCELURL'] = $url;
        $this->log('Set param CANCELURL: ' . $url . '.');
    }

    /**
     * Run a SetExpressCheckout API operation, receieve an authorization token, and
     * redirect user to PayPal login.
     *
     * @return  bool|string  FALSE if unable to generate redirect URL, string otherwise.
     */
    public function getRedirectUrl()
    {
        // Set API operation and API version number
        $this->params['METHOD']  = 'SetExpressCheckout';
        $this->params['VERSION'] = self::API_VERSION;

        // Order subtotal is the total
        if ( ! isset($this->params['PAYMENTREQUEST_0_ITEMAMT'])) {
            $this->params['PAYMENTREQUEST_0_ITEMAMT'] = $this->params['PAYMENTREQUEST_0_AMT'];
        // Sanitize existing subtotal
        } else {
            $this->params['PAYMENTREQUEST_0_ITEMAMT'] = number_format($this->params['PAYMENTREQUEST_0_ITEMAMT'], 2, '.', '');
        }

        // For debugging
        $this->log('Set param METHOD: ' . $this->params['METHOD'] . '.');
        $this->log('Set param VERSION: ' . $this->params['VERSION'] . '.');
        $this->log('Executing API operation: ' . $this->params['METHOD'] . '.');

        // Execute API operation
        $api = $this->callApi($this->params);

        // Operation failed
        if ( ! isset($api['ACK']) || 'Success' != $api['ACK'] || ! isset($api['TOKEN'])) {
            $this->log('API operation failed.');
            $this->setError('We were unable to redirect you to PayPal.');
            return FALSE;
        }

        // Redirect buyer to PayPal
        $this->log('Redirect URL: ' . $this->_getRedirectUrl() . $api['TOKEN'] . '.');
        return $this->_getRedirectUrl() . $api['TOKEN'];
    }

    /**
     * When buyer is redirected back from PayPal to the specified return URL, process
     * the data that PayPal sent back.
     *
     * @return  bool  FALSE if lacking required return URL data, TRUE if data supplied.
     */
    public function processReturnUrl()
    {
        // For debugging
        $this->log('Processing return URL data.');

        // Something has already went wrong further up the stack, stop here
        if ( ! $this->isValid()) {
            $this->log('Unable to process return URL. Errors already exist.');
            return FALSE;
        }

        // Not a valid PayPal return URL because PayPal always sends back this data
        if ( ! isset($_GET['token']) || ! isset($_GET['PayerID'])) {
            $this->log('Unable to process return URL. Missing required GET params.');
            $this->setError('We were unable to verify the PayPal response.');
            return FALSE;
        }

        // For debugging
        $this->log('Saving token to session: ' . $_GET['token'] . '.');
        $this->log('Saving payer ID to session: ' . $_GET['PayerID'] . '.');
        $this->log('Finished processing return URL data.');

        // Save data to session
        $_SESSION['PAYPAL-TOKEN']   = $_GET['token'];
        $_SESSION['PAYPAL-PAYERID'] = $_GET['PayerID'];

        // Successful
        return TRUE;
    }

    /**
     * Run a GetExpressCheckoutDetails API operation to get all of the buyer's shipping
     * and billing information. This method should only be run if setExpressCheckout() has
     * successfully redirected the buyer to PayPal, and the buyer has been redirected back
     * to the return URL with the applicable authorization token.
     *
     * @param   string      The authorization token. If not provided, will attempt to get
     *                      automatically from URL or session.
     * @return  bool|array  FALSE if operation fails, array of data otherwise.
     */
    public function getExpressCheckoutDetails($token = NULL)
    {
        // For debugging
        $this->log('Preparing to execute API operation: GetExpressCheckoutDetails.');

        // Something has already went wrong further up the stack, stop here
        if ( ! $this->isValid()) {
            $this->log('Unable to execute API operation. Errors already exist.');
            return FALSE;
        }

        // Manually set authorization token
        if (NULL !== $token) {
            $this->params['TOKEN'] = $token;
        // Use saved authorization token from prior URL (see processReturnUrl())
        } else {
            $this->params['TOKEN'] = Vine_Session::get('PAYPAL-TOKEN');
        }

        // Set API operation and API version number
        $this->params['METHOD']  = 'GetExpressCheckoutDetails';
        $this->params['VERSION'] = self::API_VERSION;
        
        // For debugging
        $this->log('Set param TOKEN: ' . $this->params['TOKEN'] . '.');
        $this->log('Set param METHOD: ' . $this->params['METHOD'] . '.');
        $this->log('Set param VERSION: ' . $this->params['VERSION'] . '.');
        $this->log('Executing API operation: ' . $this->params['METHOD'] . '.');

        // Execute API operation
        $api = $this->callApi($this->params);

        // Operation failed
        if ( ! isset($api['ACK']) || 'Success' != $api['ACK']) {
            $this->log('API operation failed.');
            $this->setError('We were unable to verify your PayPal payment authorization.');
            return FALSE;
        }

        // Default params, to help ensure E_NOTICE errors are avoided
        $default = array
        (
            'EMAIL'                              => NULL,
            'PAYERID'                            => NULL,
            'PAYERSTATUS'                        => NULL,
            'FIRSTNAME'                          => NULL,
            'LASTNAME'                           => NULL,
            'COUNTRYCODE'                        => NULL,
            'SHIPTONAME'                         => NULL,
            'SHIPTOSTREET'                       => NULL,
            'SHIPTOCITY'                         => NULL,
            'SHIPTOSTATE'                        => NULL,
            'SHIPTOZIP'                          => NULL,
            'SHIPTOCOUNTRYCODE'                  => NULL,
            'SHIPTOCOUNTRYNAME'                  => NULL,
            'PAYMENTREQUEST_0_SHIPTONAME'        => NULL,
            'PAYMENTREQUEST_0_SHIPTOSTREET'      => NULL,
            'PAYMENTREQUEST_0_SHIPTOCITY'        => NULL,
            'PAYMENTREQUEST_0_SHIPTOSTATE'       => NULL,
            'PAYMENTREQUEST_0_SHIPTOZIP'         => NULL,
            'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => NULL,
            'PAYMENTREQUEST_0_SHIPTOCOUNTRYNAME' => NULL,
        );

        // Successful
        $this->log('API operation successful.');
        $this->data = array_merge($default, $api);
        return $this->data;
    }

    /**
     * Run a DoExpressCheckoutPayment API operation to complete a PayPal Express Checkout
     * transaction. This method should only be run if setExpressCheckout() has
     * successfully redirected the buyer to PayPal, and the buyer has been redirected back
     * to the return URL with the applicable authorization token.
     *
     * @param   string  The authorization token. If not provided, will attempt to get
     *                  automatically from URL or session.
     * @param   string  The payer ID. If not povided, will attempt to get automatically
     *                  from URL or session.
     * @return  bool  TRUE when payment successful, FALSE otherwise.
     */
    public function doExpressCheckoutPayment($token = NULL, $payerId = NULL)
    {
        // For debugging
        $this->log('Preparing to execute API operation: DoExpressCheckoutPayment.');

        // Something has already went wrong further up the stack, stop here
        if ( ! $this->isValid()) {
            $this->log('Unable to execute API operation. Errors already exist.');
            return FALSE;
        }

        // Manually set authorization token
        if (NULL !== $token) {
            $this->params['TOKEN'] = $token;
        // Use saved authorization token from prior URL (see processReturnUrl())
        } else {
            $this->params['TOKEN'] = Vine_Session::get('PAYPAL-TOKEN');
        }

        // Manually set payer ID
        if (NULL !== $payerId) {
            $this->params['PAYERID'] = $payerId;
        // Use saved payer ID from prior URL (see processReturnUrl())
        } else {
            $this->params['PAYERID'] = Vine_Session::get('PAYPAL-PAYERID');
        }

        // Set API operation and API version number
        $this->params['METHOD']  = 'DoExpressCheckoutPayment';
        $this->params['VERSION'] = self::API_VERSION;
        
        // For debugging
        $this->log('Set param TOKEN: ' . $this->params['TOKEN'] . '.');
        $this->log('Set param PAYERID: ' . $this->params['PAYERID'] . '.');
        $this->log('Set param METHOD: ' . $this->params['METHOD'] . '.');
        $this->log('Set param VERSION: ' . $this->params['VERSION'] . '.');
        $this->log('Executing API operation: ' . $this->params['METHOD'] . '.');

        // Execute API operation
        $api = $this->callApi($this->params);

        // Operation failed
        if ( ! isset($api['ACK']) || 'Success' != $api['ACK']) {
            $this->log('API operation failed.');
            $this->setError('We were unable to verify your PayPal payment authorization.');
            return FALSE;
        }

        // Successful
        $this->transactionId = $api['PAYMENTINFO_0_TRANSACTIONID'];
        $this->log('API operation successful.');
        $this->log('Payment successful. Transaction ID: ' . $this->transactionId . '.');
        return TRUE;
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
     * Get checkout details (usually used for auto-populating form data).
     * @return  array  Empty array if no data available.
     * @see     getExpressCheckoutDetails()
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the transaction ID from a successful doExpressCheckoutPayment() operation.
     * @return  string  NULL if no transaction ID, string otherwise. 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Log data for debugging purposes.
     * @param   mixed  Data to log.
     * @return  void
     * @see     debug() 
     */
    protected function log($data)
    {
        if (is_array($data) || is_object($data)) {
            $this->logs[] = print_r($data, TRUE);
        } else {
            $this->logs[] = $data;
        }
    }

    /**
     * Run a cURL request to the PayPal NVP API.
     * @param   array  Name => Value Parameters.
     * @return  array  Name => Value Paramaters. Empty if response not valid.
     */
    protected function callApi(array $params)
    {
        // Start cURL
        $handle = curl_init();

        // Prepare cURL
        curl_setopt($handle, CURLOPT_URL, $this->_getApiUrl());
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $this->_parseRequest($params));

        // Execute cURL
        $this->lastResponse = $this->_parseResponse(curl_exec($handle));

        // Close cURL
        curl_close($handle);

        // Return usable result
        return $this->lastResponse;
    }

    /**
     * Prepare an API request by converting an array of API parameters to a valid cURL
     * POSTFIELDS string.
     *
     * @param   array
     * @return  string 
     */
    private function _parseRequest(array $params)
    {
        // Start with an empty array
        $request = array();

        // No parameters provided, return empty string
        if (empty($params)) {
            return '';
        }

        // Loop through and compile all parameters
        foreach ($params as $param => $value) {
            $request[] = $param . '=' . urlencode($value);
        }

        // Convert to valid string
        return implode('&', $request);
    }

    /**
     * Parse an API response from a cURL operation.
     * @param   string
     * @return  array   Empty if response is invalid.
     */
    private function _parseResponse($response)
    {
        // For debugging
        $this->log('Parsing API response.');

        // Start with an empty result
        $result = array();

        // Invalid response, return an empty array
        if ( ! is_string($response)) {
            $this->log('API response invalid.');
            return $result;
        }

        // Convert response to an array
        $response = explode('&', $response);

        // Response has at least 1 parameter
        if (is_array($response) && ! empty($response)) {
            // Loop through response
            foreach ($response as $item) {
                // Get the response parameter name and value
                $item = explode('=', $item);

                // Valid response parameter, append to result
                if (is_array($item) && isset($item[1])) {
                    $result[urldecode($item[0])] = urldecode($item[1]);
                }
            }
        }

        // Return usable result (response)
        $this->log('Finished parsing API response.');
        return $result;
    }

    /**
     * Get the URL to the PayPal API.
     * @return  string
     */
    private function _getApiUrl()
    {
        if (TRUE === $this->testMode) {
            return self::API_SANDBOX;
        } else {
            return self::API_PRODUCTION;
        }
    }

    /**
     * Get the base URL to redirect buyer to.
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