<?php

/**
 * Wrapper class for the Payeezy API.
 * ---
 * Visa               4111111111111111    Expiry Date: Any future date.
 * Mastercard         5500000000000004    Expiry Date: Any future date.
 * American Express   340000000000009     Expiry Date: Any future date.
 * JCB                3566002020140006    Expiry Date: Any future date.
 * Discover           6011000000000004    Expiry Date: Any future date.
 * Diners Club        36438999960016      Expiry Date: Any future date.
 * ---
 * @author  Tell Konkle
 * @date    2017-06-07
 * @see     https://developer.payeezy.com/payeezy-api/apis/post/transactions-3
 */
class Transaction_Payeezy implements Transaction_Interface_Transaction
{
    /**
     * Base URI endpoints.
     */
    const URL_LIVE = 'https://api.payeezy.com/v1/transactions';
    const URL_TEST = 'https://api-cert.payeezy.com/v1/transactions';

    /**
     * Error to use when a more detailed error message cannot be compiled.
     */
    const DEFAULT_ERROR = 'The transaction could not be processed.';

    /**
     * HTTP headers to send in every API request.
     */
    const HEADERS = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    /**
     * Merchant token.
     * ---
     * @var  string
     */
    protected $merchantToken = NULL;

    /**
     * API key.
     * ---
     * @var  string
     */
    protected $apiKey = NULL;

    /**
     * API Secret key. Used to sign HMAC tokens.
     * ---
     * @var  string
     */
    protected $apiSecret = NULL;

    /**
     * The first name on the credit card.
     * ---
     * @var  string
     */
    protected $firstName = NULL;

    /**
     * The last name on the credit card.
     * ---
     * @var  string
     */
    protected $lastName = NULL;

    /**
     * Human-readable error message.
     * ---
     * @var  string
     */
    protected $error = NULL;

    /**
     * Custom HTTP headers to send in request.
     * ---
     * @var  array
     */
    protected $headers = [];

    /**
     * Credit card object. Gets placed in $this->request when applicable.
     * ---
     * @var  array
     */
    protected $card = [
        'type'            => NULL,
        'cvv'             => NULL,
        'cardholder_name' => NULL,
        'card_number'     => NULL,
        'exp_date'        => NULL,
    ];

    /**
     * Billing address object. Gets placed in $this->request when applicable.
     * ---
     * @var  array
     */
    protected $address = [
        'street'          => NULL,
        'city'            => NULL,
        'state_province'  => NULL,
        'zip_postal_code' => NULL,
        'country'         => NULL,
        'email'           => NULL,
    ];

    /**
     * Compiled HTTP request body (JSON encoding of finalized $this->request array).
     * ---
     * @var  string
     */
    protected $body = NULL;

    /**
     * An array containing all of the parameters and values to send in the next API
     * operation.
     * ---
     * @var  array
     */
    protected $request = [
        'currency_code' => 'USD',
    ];

    /**
     * An array containing the last API response.
     * ---
     * @var  array
     */
    protected $response = [];

    /**
     * ID of the last successful transaction, or ID manually set from a previous
     * transaction.
     * ---
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * Tag of the last successful transaction, or tag manually set from a previous
     * transaction. Used to process secondary/split transactions.
     * ---
     * @var  mixed
     */
    protected $transactionTag = NULL;

    /**
     * Enable/disable test mode.
     * ---
     * @var  bool
     */
    protected $testMode = FALSE;

    /**
     * Class constructor. Set access credentials.
     * ---
     * @param   string
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct(
        $merchantToken,
        $apiKey,
        $apiSecret,
        $testMode = FALSE
    ) {
        $this->merchantToken = trim($merchantToken);
        $this->apiKey        = trim($apiKey);
        $this->apiSecret     = trim($apiSecret);
        $this->testMode      = (bool) $testMode;
    }

    /**
     * Class destructor. Used for debugging in test mode only. Do NOT enable test mode on
     * a production server because it violates PCI compliancy laws to put real card
     * numbers in server logs or non-RAM memory banks without adaquate encryption.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Only log when in test mode
        if ( ! $this->testMode) {
            return;
        }

        // Compile log data
        $data = "HEADERS:\n\n"  . print_r($this->headers, TRUE) . "\n"
              . "REQUEST:\n\n"  . print_r($this->request, TRUE) . "\n"
              . "RESPONSE:\n\n" . print_r($this->response, TRUE);

        // Put the log into the log directory
        Vine_Log::logManual($data, 'payeezy.log');
    }

    /**
     * Get human-readable error message.
     * ---
     * @return  string  NULL if no error found.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set human readable error message.
     * ---
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
     * ---
     * @return  bool  TRUE if no errors found, FALSE otherwise.
     */
    public function isValid()
    {
        return NULL === $this->error;
    }

    /**
     * Get transaction ID generated from last successful transaction.
     * ---
     * @return  string  NULL if no transaction ID present, string otherwise.
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Get transaction tag generated from last successful transaction.
     * ---
     * @return  string  NULL if no transaction tag present, string otherwise.
     */
    public function getTransactionTag()
    {
        return $this->transactionTag;
    }

    /**
     * Manually set transaction ID from previous preauth transaction or equivalent.
     * ---
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
    }

    /**
     * Manually set transaction tag from a previous transaction.
     * ---
     * @param   string
     * @return  void
     */
    public function setTransactionTag($tag)
    {
        $this->transactionTag = $tag;
    }

    /**
     * Set the first name on card.
     * ---
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Set the last name on card.
     * ---
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Set the address on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->address['street'] = trim($address1);
    }

    /**
     * Set the second line of address on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        if (strlen(trim($address2))) {
            $this->address['street'] .= ' ' . trim($address2);
            $this->address['street']  = trim($this->address['street']);
        }
    }

    /**
     * Set the city on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->address['city'] = trim($city);
    }

    /**
     * Set the state/province on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->address['state_province'] = trim($province);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * ---
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->address['country'] = trim($country);
    }

    /**
     * Set the zip/postal code on file for card.
     * ---
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->address['zip_postal_code'] = trim($code);
    }

    /**
     * Set the card number.
     * ---
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->card['card_number'] = preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Set the card's expiration date.
     * ---
     * @param   string|int  Expiration month. Must be two digits (01-12).
     * @param   string|int  Expiration year. Must be four digits (2014).
     * @return  void
     */
    public function setExpirationDate($month, $year)
    {
        // (string) Clean month
        if (1 === strlen($month)) {
            $month = '0' . $month;
        }

        // (string) Clean year (get last 2 digits of a YYYY format)
        $year = substr($year , (strlen($year) - 2), 2);

        // (string) Expiration date in MMYY format
        $this->card['exp_date'] = $month . $year;
    }

    /**
     * Set the card's security code.
     * ---
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->card['cvv'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->request['currency_code'] = trim(strtoupper($currency));
    }

    /**
     * Sets the email address of buyer.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        $this->address['email'] = trim($email);
    }

    /**
     * Set a custom paramater to the API.
     * @param   string  The parameter name.
     * @param   string  The parameter value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        Vine_Array::setKey($this->request, $param, $value);
    }

    /**
     * Do a pre-authorization transaction.
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        // (string) Compile authorize-specific request parameters
        $this->request['transaction_type'] = 'authorize';
        $this->request['method']           = 'credit_card';

        // (string) Normalize amount to cents (or applicable base currency unit)
        $this->request['amount'] = number_format((float) $amount, 2, '', '');

        // Add billing address object to request
        $this->applyAddress();

        // Add credit card object to request
        $this->applyCard();

        // (bool) Process request
        return $this->process();
    }

    /**
     * Do a capture on a prior authorization.
     *
     * [!!!] The transaction ID must already be set.
     * [!!!] The transaction tag must already be set.
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setTransactionTag()
     */
    public function doPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete capture. Missing transaction ID.');
            return FALSE;
        }

        // Transaction Tag is required to do a capture on a prior authorization
        if (NULL === $this->transactionTag) {
            $this->setError('Unable to complete capture. Missing transaction tag.');
            return FALSE;
        }

        // (string) Compile capture-specific request parameters
        $this->request['transaction_tag']  = $this->transactionTag;
        $this->request['transaction_type'] = 'capture';
        $this->request['method']           = 'credit_card';

        // (string) Normalize amount to cents (or applicable base currency unit)
        $this->request['amount'] = number_format((float) $amount, 2, '', '');

        // (bool) Process request
        return $this->process($this->transactionId);
    }

    /**
     * Do an authorization and capture (sale).
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        // (string) Compile sale-specific request parameters
        $this->request['partial_redemption'] = FALSE;
        $this->request['transaction_type']   = 'purchase';
        $this->request['method']             = 'credit_card';

        // (string) Normalize amount to cents (or applicable base currency unit)
        $this->request['amount'] = number_format((float) $amount, 2, '', '');

        // Add billing address object to request
        $this->applyAddress();

        // Add credit card object to request
        $this->applyCard();

        // (bool) Process request
        return $this->process();
    }

    /**
     * Do a credit/refund on a previous transaction.
     * ---
     * [!!!] The transaction ID and transaction tag must already be set.
     * ---
     * @param   string|float  The credit amount. Not all API's support amount.
     * @return  bool          TRUE if credit/refund successful, FALSE otherwise.
     */
    public function doCredit($amount)
    {
        // Transaction ID is required to do a credit
        if (NULL === $this->transactionId) {
            $this->setError('Unable to complete credit. Missing transaction ID.');
            return FALSE;
        }

        // Transaction tag is required to do a credit
        if (NULL === $this->transactionTag) {
            $this->setError('Unable to complete credit. Missing transaction tag.');
            return FALSE;
        }

        // (string) Compile credit-specific request parameters
        $this->request['transaction_tag']  = $this->transactionTag;
        $this->request['transaction_type'] = 'refund';
        $this->request['method']           = 'credit_card';

        // (string) Normalize amount to cents (or applicable base currency unit)
        $this->request['amount'] = number_format((float) $amount, 2, '', '');

        // (bool) Process request
        return $this->process($this->transactionId);
    }

    /**
     * Process the API operation as applicable.
     * ---
     * @param   string  (Optional) URI endpoint (gets appended to base URI).
     * @return  bool    TRUE if transaction successful, FALSE otherwise.
     */
    protected function process($uri = NULL)
    {
        // (string) Generate HTTP request (sent as a JSON string)
        $request = $this->prepareData();

        // (string) Base endpoint URL
        $url = $this->testMode ? self::URL_TEST : self::URL_LIVE;

        // (string) Append URI to base endpoint URL
        if ($uri) {
            $url .= '/' . ltrim($uri, '/');
        }

        // Initializing curl
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_HEADER, FALSE);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_POST, TRUE);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $request);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);

        // Execute HTTP request and get response and HTTP status code
        $response = curl_exec($handle);
        $status   = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        // Close cURL handle
        curl_close($handle);

        // Request failed completely, stop here
        if ( ! $response) {
            $this->setError(self::DEFAULT_ERROR);
            return $this->response = FALSE;
        }

        // (array|null) Save the last response (JSON --> PHP array)
        $this->response = Vine_Json::decode($response, TRUE);

        // Couldn't decode JSON string
        if ( ! is_array($this->response)) {
            $this->setError(self::DEFAULT_ERROR);
            return $this->response = FALSE;
        }

        // (bool) Parse response
        return $this->parseResponse($this->response, $status);
    }

    /**
     * Parse an API response.
     * ---
     * @param   array  Decoded JSON array.
     * @param   int    HTTP status code.
     * @return  bool   TRUE if response was positive, FALSE if response was negative.
     */
    protected function parseResponse(array $response, $code)
    {
        // Invalid response, stop here
        if ( ! isset($response['transaction_status'])) {
            $this->setError(self::DEFAULT_ERROR);
            return $this->response = FALSE;
        }

        // (array) Save decoded JSON response
        $this->response = $response;

        // Transaction was successful, save ID and tag and safely stop here
        if (0 === strcasecmp($response['transaction_status'], 'Approved')) {
            $this->transactionId  = Vine_Array::getKey($response, 'transaction_id');
            $this->transactionTag = Vine_Array::getKey($response, 'transaction_tag');
            return TRUE;
        }

        // Save error message, safely stop here
        if (isset($response['Error']['messages']['description'])) {
            $this->setError($response['Error']['messages']['description']);
            return FALSE;
        }

        // More than one error message, use the first one, safely stop here
        if (isset($response['Error']['messages'][0]['description'])) {
            $this->setError($response['Error']['messages'][0]['description']);
            return FALSE;
        }

        // Unable to parse response
        $this->setError(self::DEFAULT_ERROR);
        return FALSE;
    }

    /**
     * Make final adjustments to the parameter data before performing an API operation.
     * ---
     * @return  string  Request body in JSON object format.
     */
    protected function prepareData()
    {
        // (string) Compile request into JSON string (JSON body, not POST fields)
        $request = Vine_Json::encode(
            $this->request,
            $this->testMode ? (JSON_FORCE_OBJECT | JSON_PRETTY_PRINT) : JSON_FORCE_OBJECT
        );

        // Now prepare headers so proper HMAC generation can occur using request payload
        $this->prepareHeaders($request);

        // (string) JSON request string
        return $request;
    }

    /**
     * Generate HTTP headers needed for API request.
     * ---
     * @param   string  Request payload (i.e. request body).
     * @return  void
     */
    protected function prepareHeaders($payload)
    {
        // (string) Epoch time (milliseconds) needed to compile HMAC token
        $time = strval(time() * 1000);

        // (string) Random nonce string needed to compile HMAC token
        $nonce = strval(hexdec(bin2hex(openssl_random_pseudo_bytes(4))));

        // (string) HMAC hash data
        $data = $this->apiKey . $nonce . $time . $this->merchantToken . $payload;

        // (string) Compile and encode HMAC hash
        $hmac = base64_encode(hash_hmac('sha256', $data, $this->apiSecret, FALSE));

        // (array) Start with default headers
        $this->headers = self::HEADERS;

        // Add additional headers
        $this->headers[] = 'apikey: ' . strval($this->apiKey);
        $this->headers[] = 'token: ' . strval($this->merchantToken);
        $this->headers[] = 'Authorization: ' . $hmac;
        $this->headers[] = 'nonce: ' . $nonce;
        $this->headers[] = 'timestamp: ' . $time;
    }

    /**
     * Apply billing addess object to request.
     * ---
     * @return  void
     */
    protected function applyAddress()
    {
        // (array) Remove NULL values from credit card object
        $address = array_filter($this->address, function($value) {
            return ! is_null($value);
        });

        // Apply billing address object
        if ( ! empty($address)) {
            $this->request['billing_address'] = $address;
        // Nothing to apply, remove billing address object
        } else {
            unset($this->request['billing_address']);
        }
    }

    /**
     * Apply credit card object to request.
     * ---
     * @return  void
     */
    protected function applyCard()
    {
        // (array) Make a copy of card object for this API request
        $card = $this->card;

        // (string) Compile cardholder's full name
        $card['cardholder_name'] = trim($this->firstName . ' ' . $this->lastName);

        // (array) Remove NULL values and empty strings from credit card object
        $card = array_filter($card, function($value) {
            return ! is_null($value) && strlen($value);
        });

        // Nothing to apply, remove credit card object from request, stop here
        if (empty($card)) {
            unset($this->request['credit_card']);
            return;
        }

        // An API shouldn't require a card type as a lazy string, but whatever... :-/
        switch (Vine_Payment::getCardType($card['card_number'])) {
            case 'Visa':
                $card['type'] = 'Visa';
                break;
            case 'Amex':
                $card['type'] = 'American Express';
                break;
            case 'Discover':
                $card['type'] = 'Discover';
                break;
            case 'MasterCard':
                $card['type'] = 'Mastercard';
                break;
            default:
                $card['type'] = 'Visa';
            break;
        }

        // Apply credit card object to request
        $this->request['credit_card'] = $card;
    }
}