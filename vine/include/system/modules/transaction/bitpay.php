<?php

/**
 * Wrapper class for the BitPay bitcoin system.
 *
 * @author  Tell Konkle
 * @date    2014-02-16
 */
class Transaction_BitPay
{
    /**
     * BitPay API communication URL.
     */
    const API_URL = 'https://bitpay.com/api/invoice';

    /**
     * Default BitPay errors.
     */
    const DEFAULT_REDIRECT_ERROR = 'Unable to complete BitPay request.';
    const DEFAULT_VERIFY_ERROR   = 'Authentication failed for BitPay transaction.';

    /**
     * Default BitPay transaction speed.
     */
    const DEFAULT_SPEED = 'high';

    /**
     * The BitPay backend API key.
     * @var  string
     * @see  __construct()
     */
    protected $apiKey = NULL;

    /**
     * The buyer's first name.
     * @var  string
     * @see  setFirstName()
     */
    protected $firstName = NULL;

    /**
     * The buyer's last name.
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
     * Parameters to use when redirecting buyer to BitPay.
     * @var  array
     * @see  getRedirectUrl()
     */
    protected $params = array();

    /**
     * Invoice ID of the last generated invoice, or ID manually set from a previously
     * generated invoice.
     *
     * @var  string
     * @see  setTransactionId()
     * @see  getTransactionId()
     */
    protected $transactionId = NULL;

    /**
     * Whether or not the loaded order has been paid.
     * @var  bool
     * @see  isComplete()
     */
    protected $isComplete = FALSE;

    /**
     * Class constructor. Set BitPay API key.
     * @return  void
     */
    public function __construct($apiKey)
    {
        $this->apiKey = trim($apiKey);
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
     * See whether or not the loaded order has been paid.
     * @return  bool
     */
    public function isComplete()
    {
        return (bool) $this->isComplete;
    }

    /**
     * Get transaction ID generated from last generated invoice.
     * @return  string  NULL if no transaction ID present, string otherwise.
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Manually set transaction ID from previously generated invoice.
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
    }
    /**
     * The the order's ID, UUID, GUID, or other form of reference ID.
     * @param   string|int
     * @return  void
     */
    public function setOrderId($id)
    {
        $this->params['orderID'] = $id;
    }

    /**
     * Set the speed and priority of the transaction. Valid values are detailed below:
     *
     *    'high'   : An invoice is considered to be "confirmed" immediately upon receipt
     *               of payment.
     *
     *    'medium' : An invoice is considered to be "confirmed" after 1 block
     *               confirmation (~10 minutes).
     *
     *    'low'    : An invoice is considered to be "confirmed" after 6 block
     *               confirmations (~1 hour).
     *
     * @param type $speed
     */
    public function setSpeed($speed)
    {
        // Standardize
        $speed = strtolower($speed);

        // Use default transaction speed
        if ( ! in_array($speed, array('high', 'medium', 'low'))) {
            $this->params['transactionSpeed'] = self::DEFAULT_SPEED;
        // Use specified transaction speed
        } else {
            $this->params['transactionSpeed'] = $speed;
        }
    }

    /**
     * Set the order total and applicable currency code.
     * @param   float|string|int  The order total.
     * @param   string            (opt) The current code. Defaults to USD.
     * @return  void
     */
    public function setAmount($amount, $currency = 'USD')
    {
        $this->params['price']    = number_format((float) $amount, 2, '.', '');
        $this->params['currency'] = strtoupper(trim($currency));
    }

    /**
     * Set the payment notification URL. This is the URL that BitPay will silently post
     * data to when buyer completes a purchase.
     *
     * [!!!] This URL *must* be an https URL. Unencrypted http URLs are not supported.
     *
     * @param   string
     * @return  void
     */
    public function setNotificationUrl($url)
    {
        $this->params['notificationURL'] = trim($url);
    }

    /**
     * Set the return URL. This is the URL that the buyer will get redirected to once
     * they've completed their purchase at BitPay and choose to go back to the merchant's
     * website.
     *
     * @param   string
     * @return  void
     */
    public function setReturnUrl($url)
    {
        $this->params['redirectURL'] = trim($url);
    }

    /**
     * Set the buyer's first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);
    }

    /**
     * Set the buyer's last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);
    }

    /**
     * Set the buyer's address 1.
     * @param   string
     * @return  void
     */
    public function setAddress1($address)
    {
        $address = trim($address);

        if ($address) {
            $this->params['buyerAddress1'] = $address;
        }
    }

    /**
     * Set the buyer's address 2.
     * @param   string
     * @return  void
     */
    public function setAddress2($address)
    {
        $address = trim($address);

        if ($address) {
            $this->params['buyerAddress2'] = $address;
        }
    }

    /**
     * Set the buyer's city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $city = trim($city);

        if ($city) {
            $this->params['buyerCity'] = $city;
        }
    }

    /**
     * Set the buyer's state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $province = trim($province);

        if ($province) {
            $this->params['buyerState'] = $province;
        }
    }

    /**
     * Set the buyer's zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        $postal = trim($postal);

        if ($postal) {
            $this->params['buyerZip'] = $postal;
        }
    }

    /**
     * Set billing country.
     * @param   string
     * @return  void 
     */
    public function setCountry($country)
    {
        $country = trim($country);

        if ($country) {
            $this->params['buyerCountry'] = $country;
        }
    }

    /**
     * Create a BitPay invoice and get URL needed to redirect buyer to BitPay.
     * @return  string|bool  FALSE if no URL could be generated, string otherwise.
     */
    public function getRedirectUrl()
    {
        // Compile first and last name
        if ($this->firstName || $this->lastName) {
            $this->params['buyerName'] = trim($this->firstName . ' ' . $this->lastName);
        }

        // Notify IPN script of every invoice status change
        $this->params['fullNotifications'] = TRUE;

        // Prepare POS Data (used to verify payment notifications
        $this->_preparePos();

        // Send cURL request
        $resp = $this->_sendRequest(self::API_URL, $this->params);

        // Invalid request, no error given, use default error
        if ( ! $resp && ! $this->getError()) {
            $this->setError(self::DEFAULT_REDIRECT_ERROR);
            return FALSE;
        }

        // Invalid response
        if ( ! isset($resp['status'])
          || ! isset($resp['url'])
          || 'new' !== $resp['status']
        ) {
            $this->setError(self::DEFAULT_REDIRECT_ERROR);
            return FALSE;
        }

        // Save invoice ID
        if (isset($resp['id'])) {
            $this->transactionId = $resp['id'];
        }

        // (string) Valid response, return the redirect url
        return $resp['url'];
    }

    /**
     * Verify a payment notification and get payment info.
     * @return  bool|array  FALSE if notification invalid, array otherwise.
     */
    public function verifyNotification()
    {
        // POST request with JSON encoding
        $post = file_get_contents('php://input');

        // Invalid request
        if ( ! $post) {
            return FALSE;
        }

        // Convert JSON to PHP array
        $json = Vine_Json::decode($post, TRUE);

        // Invalid request
        if (is_string($json)) {
            $this->setError($json);
            return FALSE;
        }

        // Incomplete request
        if ( ! isset($json['posData'])) {
            $this->setError(self::DEFAULT_VERIFY_ERROR);
            return FALSE;
        }
        
        // Invalid notification
        if ( ! $this->_verifyPos($json['posData'])) {
            $this->setError(self::DEFAULT_VERIFY_ERROR);
            return FALSE;
        }

        // Save invoice ID
        if (isset($json['id'])) {
            $this->transactionId = $json['id'];
        }

        // Determine whether or not order is really completed
        if (isset($json['status'])) {
            $this->isComplete = in_array($json['status'], array
            (
                'paid',
                'confirmed',
                'complete',
            ));
        // Order has not been completed
        } else {
            $this->isComplete = FALSE;
        }

        // (array) This is a valid notification
        return $json;
    }

    /**
     * Get all of the info for a specified invoice/order.
     * @return  array|bool  FALSE if order not found, array otherwise.
     */
    public function getOrder()
    {
        try {
            // Verify that the invoice/transaction ID has been set
            if (NULL === $this->transactionId) {
                throw new LogicException('No transaction ID has been set.');
            }

            // Get order info
            $info = $this->_sendRequest(self::API_URL . '/' . $this->transactionId);

            // Determine whether or not order is really completed
            if (isset($info['status'])) {
                $this->isComplete = in_array($info['status'], array
                (
                    'paid',
                    'confirmed',
                    'complete',
                ));
            // Order has not been completed
            } else {
                $this->isComplete = FALSE;
            }

            // (bool|array)
            return $info;
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
            return FALSE;
        }
    }

    /**
     * Check the status of a specified invoice/order.
     * @return  string  'new', 'paid', 'confirmed', 'complete', 'expired', 'invalid'.
     */
    public function getOrderStatus()
    {
        try {
            // Verify that the invoice/transaction ID has been set
            if (NULL === $this->transactionId) {
                throw new LogicException('No transaction ID has been set.');
            }

            // (bool|array)
            $resp = $this->_sendRequest(self::API_URL . '/' . $this->transactionId);

            // Invalid response or order not found
            if ( ! $resp || ! isset($resp['status'])) {
                return FALSE;
            }

            // Determine whether or not order is really completed
            if (isset($resp['status'])) {
                $this->isComplete = in_array($resp['status'], array
                (
                    'paid',
                    'confirmed',
                    'complete',
                ));
            // Order has not been completed
            } else {
                $this->isComplete = FALSE;
            }

            // (string)
            return $resp['status'];
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
            return FALSE;
        }
    }

    /**
     * Send a cURL request to the BitPay API.
     * @param   string      The URL to send request to.
     * @param   mixed       (opt) Data to send in the request.
     * @return  bool|array  FALSE if request failed, array otherwise.
     */
    private function _sendRequest($url, $data = FALSE)
    {
        // JSON encoded data if it hasn't already been encoded
        if ($data && ! is_string($data)) {
            $data = Vine_Json::encode($data);
        }

        // Request headers
        $header = array
        (
            'Content-Type: application/json',
            'Content-Length: ' . ($data ? strlen($data) : 0),
            'Authorization: Basic ' . base64_encode($this->apiKey),
        );

        // Prepare cURL request
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_PORT, 443);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
        curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($handle, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($handle, CURLOPT_FRESH_CONNECT, 1);

        // Include POST data with this request
        if ($data) {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        }

        // Execute request
        $resp  = curl_exec($handle);
        $error = curl_error($handle);

        // Close cURL
        curl_close($handle);

        // Invalid response
        if ( ! $resp) {
            $this->setError($error);
            return FALSE;
        // Convert JSON response to PHP array
        } else {
            $resp = Vine_Json::decode($resp, TRUE);
        }

        // (bool|array)
        return $resp ? $resp : FALSE;
    }

    /**
     * Prepare posData parameter which is later used to verify the notifications.
     * @return  string
     */
    private function _preparePos()
    {
        // A random string
        $salt = Vine_Security::makeRandomString('16');

        // Generate posData parameter
        $this->params['posData'] = Vine_Json::encode(array
        (
            'data' => $salt,
            'hash' => hash_hmac('md5', $salt, $this->apiKey),
        ));
    }

    /**
     * Verify notification posData.
     * @param   string  JSON encoded POS data.
     * @return  bool
     */
    private function _verifyPos($pos)
    {
        // Decode JSON encoded string
        if (is_string($pos)) {
            $pos = Vine_json::decode($pos, TRUE);
        }

        // Required data not even provided, obviously invalid
        if ( ! isset($pos['data']) || ! isset($pos['hash'])) {
            return FALSE;
        }

        // (bool)
        return $pos['hash'] === hash_hmac('md5', $pos['data'], $this->apiKey);
    }
}