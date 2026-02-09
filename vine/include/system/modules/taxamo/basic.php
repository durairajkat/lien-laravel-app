<?php

// Dependencies
require_once 'taxamo-php/lib/Taxamo.php';

/**
 * Basic wrapper class for Taxamo's Swagger-codegen PHP library.
 *
 * @author  Tell Konkle
 * @date    2015-09-11
 */
class Taxamo_Basic
{
    /**
     * Class constants.
     */
    const API_URL        = "https://api.taxamo.com";
    const ERROR_DEFAULT  = "Tax calculation failed. Contact vendor for details.";
    const ERROR_MISMATCH = "Tax calculation mismatch. Please contact vendor.";
    const ERROR_COUNTRY  = "Couldn't determine user's country based on provided details.";
    const SESSION_TRANS  = "taxamo-transaction-key-v1.0:";

    /**
     * Private API key.
     * @var  string
     * @see  __construct()
     */
    protected $privateKey = NULL;

    /**
     * Public API key.
     * @var  string
     * @see  __construct()
     */
    protected $publicKey = NULL;

    /**
     * API object. Used to call an API endpoint manually, skipping the Swagger library.
     * @var  object  Instance of APIClient.
     * @see  __construct()
     */
    protected $client = NULL;

    /**
     * Taxamo object.
     * @var  object  Instance of Taxamo.
     * @see  __construct()
     */
    protected $taxamo = NULL;

    /**
     * The last API url operation.
     * @var  string
     * @see  debug()
     */
    protected $endpoint = NULL;

    /**
     * Transaction object.
     * @var  object  Instance of Input_transaction().
     * @see  __construct()
     */
    protected $transaction = NULL;

    /**
     * The latest response from the Taxamo API.
     * @var  object
     * @see  parseTransactionResponse()
     */
    protected $response = NULL;

    /**
     * The transaction key/ID.
     * @var  string
     * @see  getTransactionKey()
     * @see  parseTransactionResponse()
     */
    protected $transactionKey = NULL;

    /**
     * The parsed transaction lines.
     * @see  array
     * @see  getItems()
     * @see  parseTransactionResponse()
     */
    protected $items = NULL;

    /**
     * Miscellaneous data received in response.
     * @var  mixed
     * @see  parseTransactionResponse()
     */
    protected $subtotal = '0.00';
    protected $shipping = '0.00';
    protected $taxes    = '0.00';
    protected $total    = '0.00';
    protected $details  = NULL;

    /**
     * Latest API error.
     * @var  string
     * @see  getError()
     * @see  isValid()
     */
    protected $error = NULL;

    /**
     * Invoice address data.
     * @var  array
     * @see  setAddress1()
     * @see  setAddress2()
     * @see  setCity()
     * @see  setProvince()
     * @see  setPostal()
     * @see  setCountry()
     */
    protected $address = array();

    /**
     * A complete array of ISO-3166 countries.
     * @var  array
     * @see  __consruct()
     */
    protected $countries = array();

    /**
     * An array of settings for Taxamo account.
     * @var  array
     * @see  _getSettings()
     */
    protected $settings = array();

    /**
     * Manually set IP address.
     * @var  string
     * @see  setIp()
     */
    protected $ip = NULL;

    /**
     * Class constructor. Set API key and source ID.
     * @param   string  The private API key.
     * @param   string  The public API key.
     * @param   string  (opt) The source/affiliate/plugin ID.
     * @return  void
     */
    public function __construct($privateKey, $publicKey, $sourceId = NULL)
    {
        // Save keys
        $this->privateKey = $privateKey;
        $this->publicKey  = $publicKey;

        // Initialize API client
        $this->client = new APIClient($privateKey, self::API_URL);

        // Add source ID
        if ($sourceId) {
            $this->client->sourceId = $sourceId;
        }

        // Create instance of Taxamo & Input_transaction, get country list
        $this->taxamo      = new Taxamo($this->client);
        $this->transaction = new Input_transaction();
        $this->countries   = require VINE_PATH . 'countries.php';

        // Get settings
        $this->_getSettings($publicKey);
    }

    /**
     * Clear all session cache pertaining to Taxamo.
     * @return  void
     */
    public function clearCache()
    {
        unset($_SESSION[self::SESSION_TRANS . $this->publicKey]);
    }

    /**
     * Reset Taxamo's transaction lines and custom fields lines so these lines don't
     * accidentally don't get sent to the API twice in the same request.
     *
     * @return  void
     */
    public function reset()
    {
        $this->error                          = NULL;
        $this->transaction->custom_fields     = NULL;
        $this->transaction->transaction_lines = NULL;
    }

    /**
     * Debugging tool.
     * @param   bool   (opt) Display debugging data? TRUE = Yes, FALSE = No.
     * @return  array  Debugging data
     */
    public function debug($show = FALSE)
    {
        // Compile summary data
        $debug = array
        (
            'Settings' => $this->settings,
            'Taxamo'   => $this->taxamo,
            'Endpoint' => $this->endpoint,
            'Request'  => $this->transaction,
            'Response' => $this->response,
            'Items'    => $this->items,
            'Details'  => $this->details,
            'Summary'  => array
            (
                'Subtotal'        => $this->subtotal,
                'Shipping'        => $this->shipping,
                'Taxes'           => $this->taxes,
                'Total'           => $this->total,
                'Transaction Key' => ($this->transactionKey ? $this->transactionKey : '-none-'),
                'Error'           => ($this->error ? $this->error : '-none-'),
            ),
        );

        // Security
        $debug['Taxamo']->apiClient->apiKey = '-hidden-for-security-';

        // Display
        if ($show) {
            Vine_Debug::dump($debug);
        }

        // (string)
        return $debug;
    }

    /**
     * Get the latest error returned by the API.
     * @return  string|bool  FALSE if no error found, string otherwise.
     */
    public function getError()
    {
        return $this->error ? $this->error : FALSE;
    }

    /**
     * See if the operations have been successful.
     * @return  bool  TRUE if everything went OK, FALSE otherwise.
     */
    public function isValid()
    {
        return $this->error ? FALSE : TRUE;
    }

    /**
     * Has a conflicting country evidence been found?
     * @return  bool
     */
    public function conflictFound()
    {
        // Everything is valid, no conflict found
        if ($this->isValid()) {
            return FALSE;
        }

        // Country not detected
        if (self::ERROR_COUNTRY === $this->error) {
            // The country wasn't even sent
            if ( ! isset($this->address['country'])) {
                return FALSE;
            // Conflict found
            } else {
                return TRUE;
            }
        }

        // Conflict not found
        return FALSE;
    }

    /**
     * See if self-declaration field should be shown if country couldn't be verified.
     * @var  bool
     */
    public function shouldSelfDeclare()
    {
        // Gather data
         $invalid  = ! $this->isValid();
         $conflict = $this->conflictFound();
         $declare  = $this->allowSelfDeclaration();

         // (bool)
         return $invalid && $conflict && $declare;
    }

    /**
     * See if merchant has enabled B2B transactions. If so, VAT field should be shown.
     * @return  bool  TRUE if VAT number field should be shown, FALSE otherwise.
     */
    public function showTaxNumber()
    {
        if (isset($this->settings['allow_eu_b2b'])) {
            return (bool) $this->settings['allow_eu_b2b'];
        }

        return FALSE;
    }

    /**
     * See if merchant has enabled "Force Universal Pricing."
     * @return  bool  TRUE if universal pricing has been enabled, FALSE otherwise.
     */
    public function hasUniversalPricing()
    {
        if (isset($this->settings['force_universal_pricing'])) {
            return ! (bool) $this->settings['force_universal_pricing'];
        }

        return FALSE;
    }

    /**
     * Allow customer to declare their country of residence of evidence conflicts.
     * @return  bool
     */
    public function allowSelfDeclaration()
    {
        if (isset($this->settings['allow_self_declaration'])) {
            return (bool) $this->settings['allow_self_declaration'];
        }

        return FALSE;
    }

    /**
     * Get order subtotal.
     * @return  string  The order subtotal in '0.00' format.
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * Get order's shipping total.
     * @return  string  The order shipping total in '0.00' format.
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Get order's tax total.
     * @return  string  The order tax total in '0.00' format.
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Get order total.
     * @return  string  The order total in '0.00' format.
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get tax details.
     * @return  array|bool  FALSE if no tax details were parsed, array otherwise.
     */
    public function getDetails()
    {
        return empty($this->details) ? FALSE : $this->details;
    }

    /**
     * Get the transaction key (ID) from the last API transaction object.
     * @return  string|bool  FALSE if no transaction key found, string otherwise.
     * @see     parseTransactionResponse()
     */
    public function getTransactionKey()
    {
        return $this->transactionKey ? $this->transactionKey : FALSE;
    }

    /**
     * Get the parsed transaction lines from the last API transaction object.
     * @return  array|bool  FALSE if no items found, array otherwise.
     * @see     parseTransactionResponse()
     */
    public function getItems()
    {
        return $this->items ? $this->items : FALSE;
    }

    /**
     * Manually set buyer's IP address.
     * @param   string  The buyer's IP address.
     * @return  void
     */
    public function setIp($ip)
    {
        if (strlen(trim($ip))) {
            $this->ip = trim($ip);
        }
    }

    /**
     * Set the buyer's email address.
     * @param   string  The buyer's email address.
     * @return  void
     */
    public function setEmail($email)
    {
        if (Vine_Verify::email($email)) {
            $this->transaction->buyer_email = trim($email);
        }
    }

    /**
     * Set the buyer's name. Can be first/last name, or company name.
     * @param   string  The buyer's name.
     * @return  void
     */
    public function setName($name)
    {
        if (trim($name)) {
            $this->transaction->buyer_name = trim($name);
        }
    }

    /**
     * Set the address line 1 of the buyer's invoice address.
     * @param   string  Line 1 of the invoice address.
     * @return  void
     */
    public function setAddress1($address1)
    {
        if (trim($address1)) {
            $this->address['address1'] = trim($address1);
        }
    }

    /**
     * Set the address line 2 of the buyer's invoice address.
     * @param   string  Line 2 of the invoice address.
     * @return  void
     */
    public function setAddress2($address2)
    {
        if (trim($address2)) {
            $this->address['address2'] = trim($address2);
        }
    }

    /**
     * Set the city of the buyer's invoice address.
     * @param   string  The city.
     * @return  void
     */
    public function setCity($city)
    {
        if (trim($city)) {
            $this->address['city'] = trim($city);
        }
    }

    /**
     * Set the province or region of the buyer's invoice address.
     * @param   string  The province or region.
     * @return  void
     */
    public function setProvince($province)
    {
        if (trim($province)) {
            $this->address['province'] = trim($province);
        }
    }

    /**
     * Set the postal code of the buyer's invoice address.
     * @param   string  The postal code.
     * @return  void
     */
    public function setPostal($code)
    {
        if (trim($code)) {
            $this->address['postal'] = trim($code);
        }
    }

    /**
     * Set the 2-letter ISO-3166 country code of the buyer's invoice address.
     * @param   string  The 2-letter country code.
     * @param   bool    (opt) Force this country code and bypass country detection?
     * @return  void
     */
    public function setCountry($country, $force = FALSE)
    {
        // No country, stop here
        if ( ! strlen(trim($country))) {
            return;
        }

        // Set invoice and billing country
        $this->address['country']                = trim($country);
        $this->transaction->billing_country_code = trim($country);
        //$this->transaction->tax_country_code     = trim($country);

        // Force this billing country
        if ($force) {
            $this->transaction->force_country_code = trim($country);
        }
    }

    /**
     * Set the credit card number used in the transaction. The prefix is extracted from
     * the card number and is used to help verify location data.
     *
     * [!!!] Only the first 6 characters of the card number are sent to the API.
     *
     * @param   string  The card number or card number prefix.
     * @return  void
     */
    public function setCardNumber($number)
    {
        // Sanitize card number and only keep first 6 characters (this is PCI compliant)
        $number = substr(preg_replace('/\D/', '', $number), 0, 6);

        // There is a number to save
        if (strlen($number)) {
            $this->transaction->buyer_credit_card_prefix = $number;
        }
    }

    /**
     * Set the 3-letter currency code used in the transaction.
     * @param   string  The currency code.
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->transaction->currency_code = trim(strtoupper($currency));
    }

    /**
     * Set the EU VAT Number for the transaction.
     * @param   string  The VAT number.
     * @return  void
     */
    public function setTaxNumber($number)
    {
        if (strlen(trim($number))) {
            $this->transaction->buyer_tax_number = trim($number);
        }
    }

    /**
     * Set the transaction key of the original transaction. Used when renewing
     * subscription based services. Will use original transaction's location evidence.
     *
     * @param   string  The original transaction key.
     * @return  void
     * @see     getTransactionKey()
     */
    public function setSubscriptionKey($key)
    {
        if (strlen(trim($key))) {
            $this->transaction->original_transaction_key = trim($key);
        }
    }

    /**
     * Set order/invoice number.
     * @param   string|int|float  The order/invoice number.
     * @return  void
     */
    public function setOrderNumber($number)
    {
        if (trim($number)) {
            $this->transaction->invoice_number = trim($number);
        }
    }

    /**
     * Alias of setOrderNumber().
     * @see  setOrderNumber()
     */
    public function setInvoiceNumber($number)
    {
        $this->setOrderNumber($number);
    }

    /**
     * Manually set an API parameter not supported by this wrapper class.
     * @param   string  The API parameter.
     * @param   string  The parameter's value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        $this->transaction->{$param} = $value;
    }

    /**
     * Set a custom field for the transaction object.
     * @param   mixed  The field name/key.
     * @param   mixed  The field value.
     * @return  void
     */
    public function setCustom($key, $value)
    {
        // Create custom field
        $custom        = new Custom_fields();
        $custom->key   = $key;
        $custom->value = $value;

        // Append custom field
        $this->transaction->custom_fields[] = $custom;
    }

    /**
     * Set an order item that Taxamo should automatically calculate taxes for.
     * @param   mixed   The key/pointer/id value of the item in the cart/invoice.
     * @param   string  The item name.
     * @param   int     The item quantity.
     * @param   string  The item subtotal (qty * unit price), formatted as '0.00' string.
     * @param   string  (opt) Product type from API dictionary.
     * @param   string  (opt) The item's SKU number.
     * @return  void
     */
    public function setAutoItem(
        $key,
        $name,
        $qty,
        $subtotal,
        $type = 'default',
        $sku  = NULL
    ) {
        // Compile line item
        $line = new Input_transaction_line();
        $line->description  = Vine_Unicode::toAscii(trim($name));
        $line->quantity     = (int) $qty;
        $line->amount       = number_format((float) $subtotal, 2, '.', '');
        $line->product_type = $type;
        $line->custom_id    = 'key-' . trim($key);
        $line->informative  = FALSE;

        // Add SKU
        if (trim($sku)) {
            $line->product_code = trim($sku);
        }

        // Add line item to transaction
        $this->transaction->transaction_lines[] = $line;
    }

    /**
     * Set an order item that Taxamo should NOT automatically calculate taxes for.
     * @param   mixed   The key/pointer/id value of the item in the cart/invoice.
     * @param   string  The item name.
     * @param   int     The item quantity.
     * @param   string  The item subtotal (qty * unit price), formatted as '0.00' string.
     * @param   string  (opt) The tax rate, formatted as '0.00' string.
     * @param   string  (opt) Product type from API dictionary.
     * @param   string  (opt) The item's SKU number.
     * @return  void
     */
    public function setManualItem(
        $key,
        $name,
        $qty,
        $subtotal,
        $taxRate = '0.00',
        $type    = 'default',
        $sku     = NULL
    ) {
        // Compile line item
        $line = new Input_transaction_line();
        $line->description  = Vine_Unicode::toAscii(trim($name));
        $line->quantity     = (int) $qty;
        $line->amount       = number_format((float) $subtotal, 2, '.', '');
        $line->product_type = $type;
        $line->custom_id    = 'key-' . trim($key);
        $line->informative  = TRUE;
        $line->tax_rate     = number_format((float) $taxRate, 2, '.', '');
        $line->tax_name     = 'Specified';

        // Add SKU
        if (trim($sku)) {
            $line->product_code = trim($sku);
        }

        // Add line item to transaction
        $this->transaction->transaction_lines[] = $line;
    }

    /**
     * Set the shipping and handling cost for the order.
     * @param   string  The shipping fee, formatted as '0.00' (string).
     * @param   bool    (opt) Is shipping tax rate. Defaults to '0.00' (string).
     * @param   string  (opt) The shipping name.
     * @return  void
     */
    public function setShipping($fee, $taxRate = '0.00', $name = 'Shipping & Handling')
    {
        // Compile line item
        $line = new Input_transaction_line();
        $line->description  = Vine_Unicode::toAscii(trim($name));
        $line->quantity     = 1;
        $line->unit_price   = number_format((float) $fee, 2, '.', '');
        $line->amount       = number_format((float) $fee, 2, '.', '');
        $line->product_type = 'default';
        $line->custom_id    = 'key-shipping';
        $line->informative  = TRUE;
        $line->tax_rate     = number_format((float) $taxRate, 2, '.', '');
        $line->tax_name     = 'Specified';

        // Add line item to transaction
        $this->transaction->transaction_lines[] = $line;
    }

    /**
     * Guess the country of IP address.
     * @param   string       The private key.
     * @param   string       (opt) The IP address to check.
     * @return  string|bool  FALSE if country could not be guessed, or ISO country code.
     */
    public static function guessCountry($privateKey, $ip = NULL)
    {
        try {
            // Get IP address
            $ip = $ip ? $ip : Vine_Request::getIp();

            // Create instance of Taxamo and run API request
            $taxamo   = new Taxamo(new APIClient($privateKey, self::API_URL));
            $response = $taxamo->locateGivenIP($ip);

            // (string) ISO 3166 country code
            if (isset($response->country_code)) {
                return $response->country_code;
            }

            // Couldn't be guessed
            return FALSE;
        // Something went wrong
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * Pre-calculate the taxes for a potential transaction.
     * @return  bool  FALSE if calculation failed, TRUE otherwise.
     */
    public function calculateTax()
    {
        try {
            // Alwats set IP when calculating
            $this->transaction->buyer_ip = $this->ip ? $this->ip : Vine_Request::getIp();

            // Prepare the transaction object
            $transaction = $this->prepareTransaction();

            // Call API, save response
            $this->endpoint = '/api/v1/tax/calculate';
            $this->response = $this->taxamo->calculateTax($transaction);

            // Parse the response
            $this->parseTransactionResponse($this->response);
            return TRUE;
        // Something went wrong
        } catch (Exception $e) {
            // Save actual response
            if (isset($e->response)) {
                $this->response = Vine_Json::decode($e->response, TRUE);
            // Couldn't find response, save Exception object
            } else {
                $this->response = $e;
            }

            // Get user-friendly error message from API response
            if (isset($e->errors[0])) {
                $this->error = $e->errors[0];
            // API response didn't have user-friendly error message, use default one
            } else {
                $this->error = self::ERROR_DEFAULT;
            }

            // Method failed
            return FALSE;
        }
    }

    /**
     * Create or update a transaction on Taxamo. If the "key" parameter is set to TRUE,
     * the transaction keys will be stored and updated automatically in session. If you're
     * not sure what to put into the "key" parameter, set it to TRUE.
     *
     * @param   string|bool  TRUE = auto. NULL, FALSE, or string = manual.
     * @return  bool         FALSE if transaction could not be created, TRUE otherwise.
     */
    public function storeTransaction($key = TRUE)
    {
        try {
            // Alwats set IP when storing
            $this->transaction->buyer_ip = $this->ip ? $this->ip : Vine_Request::getIp();

            // Prepare the transaction object and get transaction key
            $trans = $this->prepareTransaction();
            $key   = TRUE === $key ? $this->getKey() : $key;

            // Update transaction
            if (strlen($key) > 1) {
                $this->endpoint = '/api/v1/transactions/:key';
                $this->response = $this->taxamo->updateTransaction($key, $trans);
            // Create transaction
            } else {
                $this->endpoint = '/api/v1/transactions/';
                $this->response = $this->taxamo->createTransaction($trans);
            }

            // Parse the create/update response
            $this->parseTransactionResponse($this->response);
            return TRUE;
        // Something went wrong
        } catch (Exception $e) {
            // Save actual response
            if (isset($e->response)) {
                $this->response = Vine_Json::decode($e->response, TRUE);
            // Couldn't find response, save Exception object
            } else {
                $this->response = $e;
            }

            // Get user-friendly error message from API response
            if (isset($e->errors[0])) {
                $this->error = $e->errors[0];
            // API response didn't have user-friendly error message, use default one
            } else {
                $this->error = self::ERROR_DEFAULT;
            }

            // Method failed
            return FALSE;
        }
    }

    /**
     * Confirm a previously created transaction. If the "key" parameter is a boolean, the
     * transaction key store in session will be confirmed. If you're not sure what to put
     * into the "key" parameter, set it to TRUE.
     *
     * @param   string|bool  bool = auto, string = manual.
     * @return  bool         FALSE if confirmation failed, TRUE otherwise.
     */
    public function confirmTransaction($key = TRUE)
    {
        try {
            // Get transaction key
            $key = is_bool($key) ? $this->getKey() : $key;

            // No transaction existed so tried to make one, but it failed too, stop here
            if ( ! $key && ! $this->storeTransaction(TRUE)) {
                return FALSE;
            }

            // Attempt to confirm the transaction
            $trans = $this->prepareTransaction();
            $key   = $this->getKey();
            $this->endpoint = '/api/v1/transactions/:key/confirm';
            $this->response = $this->taxamo->confirmTransaction($key, $trans);

            // Parse the confirmation response
            $this->parseTransactionResponse($this->response);
            return TRUE;
        // Something went wrong
        } catch (Exception $e) {
            // Save actual response
            if (isset($e->response)) {
                $this->response = Vine_Json::decode($e->response, TRUE);
            // Couldn't find response, save Exception object
            } else {
                $this->response = $e;
            }

            // Get user-friendly error message from API response
            if (isset($e->errors[0])) {
                $this->error = $e->errors[0];
            // API response didn't have user-friendly error message, use default one
            } else {
                $this->error = self::ERROR_DEFAULT;
            }

            // Method failed
            return FALSE;
        }
    }

    /**
     * Confirm payment for a specified transaction. If the "key" parameter is a boolean,
     * the transaction key store in session will be the one the payment is added to. If
     * you're not sure what to put into the "key" parameter, set it to TRUE.
     *
     * @param   string       The transaction amount, in '0.00' string format.
     * @param   string|bool  (opt) TRUE = auto. NULL, FALSE, or string = manual.
     * @param   array        (opt) Any additional transaction data.
     * @return  bool         TRUE if payment confirmation successful, FALSE otherwise.
     */
    public function confirmPayment($amount, $key = TRUE, $data = NULL)
    {
        try {
            // Prepare simple data
            $amount  = number_format((float) $amount, 2, '.', '');
            $key     = TRUE === $key ? $this->getKey() : $key;
            $details = array();

            // Valid data, compile payment details
            if (is_array($data) && ! empty($data)) {
                // Loop through all data
                foreach ($data as $k => $v) {
                    // Junk, skip
                    if ( ! strlen(trim($v))) {
                        continue;
                    }

                    // Simple key, don't save key name'
                    if (Vine_Verify::digit($k)) {
                        $details[] = trim($v);
                    // Save key name
                    } else {
                        $details[] = trim($k) . ': ' . trim($v);
                    }
                }

                // Separate each detail on new line
                $details = implode(", ", $details);
            // No payment details
            } else {
                $details = '';
            }

            // Create payment
            $payment = new CreatePaymentIn();
            $payment->amount              = $amount;
            $payment->payment_information = $details;
            $this->taxamo->createPayment($key, $payment);
            $this->endpoint = '/api/v1/transactions/:key/payments/';
            return TRUE;
        // Something went wrong
        } catch (Exception $e) {
            // Save actual response
            if (isset($e->response)) {
                $this->response = Vine_Json::decode($e->response, TRUE);
            // Couldn't find response, save Exception object
            } else {
                $this->response = $e;
            }

            // Get user-friendly error message from API response
            if (isset($e->errors[0])) {
                $this->error = $e->errors[0];
            // API response didn't have user-friendly error message, use default one
            } else {
                $this->error = self::ERROR_DEFAULT;
            }

            // Method failed
            return FALSE;
        }
    }

    /**
     * Finalize an order and confirm with Taxamo.
     *
     * - Update transaction
     * - Confirm transaction
     * - Confirm payment
     *
     * If the "key" parameter is a boolean, the transaction key store in session will be
     * the one the payment is added to. If you're not sure what to put into the "key"
     * parameter, set it to TRUE.
     *
     * @param   string       The transaction amount, in '0.00' string format.
     * @param   string|bool  (opt) bool = auto, string = manual.
     * @param   array        (opt) Any additional transaction data.
     * @return  bool         TRUE if everything successful, FALSE otherwise.
     */
    public function finishOrder($amount, $key = TRUE, $data = NULL)
    {
        // Failed to confirm the transaction, stop here
        if ( ! $this->confirmTransaction($key)) {
            return FALSE;
        }

        // Use the key that was used/created during transaction confirmation
        $key = $this->getTransactionKey();

        // (bool) Attempt to confirm payment
        return $this->confirmPayment($amount, $key, $data);
    }

    /**
     * Make final preparations on transaction object before Taxamo API request.
     * @return  array  The prepared transaction.
     */
    protected function prepareTransaction()
    {
        // Compile invoice address
        if ( ! empty($this->address)) {
            // Start compiling invoice address
            $invoice = new Invoice_address();
            $invoice->freeform_address = $this->getAddress();

            // Set address line 1
            if (isset($this->address['address1'])) {
                $invoice->street_name = $this->address['address1'];
            }

            // Set address line 2
            if (isset($this->address['address2'])) {
                $invoice->address_detail = $this->address['address2'];
            }

            // Set city
            if (isset($this->address['city'])) {
                $invoice->city = $this->address['city'];
            }

            // Set region
            if (isset($this->address['province'])) {
                $invoice->region = $this->address['province'];
            }

            // Set postal
            if (isset($this->address['postal'])) {
                $invoice->postal_code = $this->address['postal'];
            }

            // Set country
            if (isset($this->address['country'])) {
                $invoice->country = $this->address['country'];
            }

            // Set invoice address
            $this->transaction->invoice_address = $invoice;
        }

        // (array)
        return array('transaction' => $this->transaction);
    }

    /**
     * Parse a transaction response.
     * @param   object  The API response object.
     * @return  bool
     */
    protected function parseTransactionResponse($data)
    {
        // Invalid transaction object, set default error, stop here
        if (   ! $data
            || ! is_object($data)
            || ! isset($data->transaction)
            || ! $data->transaction instanceof Transaction
        ) {
            $this->error = self::ERROR_DEFAULT;
            return FALSE;
        }

        // Reset everything
        $this->subtotal = '0.00';
        $this->shipping = '0.00';
        $this->taxes    = '0.00';
        $this->total    = '0.00';
        $this->details  = NULL;

        // Simplify and parse transaction lines
        $trans = $data->transaction;
        $this->_parseTransactionLines($trans->transaction_lines);
        $this->_verifyTotals($trans);
        $this->setKey($trans);
    }

    /**
     * Get freeform invoice address.
     * @return  string
     */
    protected function getAddress() {
        // All data
        $countries = $this->countries;
        $address1  = isset($this->address['address1']) ? $this->address['address1'] : '';
        $address2  = isset($this->address['address2']) ? $this->address['address2'] : '';
        $city      = isset($this->address['city'])     ? $this->address['city']     : '';
        $province  = isset($this->address['province']) ? $this->address['province'] : '';
        $postal    = isset($this->address['postal'])   ? $this->address['postal']   : '';
        $country   = isset($this->address['country'])  ? $this->address['country']  : '';
        $country   = $country && isset($countries[$country])
                   ? $countries[$country][0]
                   : '';

        // Compile each address line
        $line1  = $address1 ? $address1 . "\n" : '';
        $line2  = $address2 ? $address2 . "\n" : '';
        $line3  = $city ? $city . ', ' : '';
        $line3 .= $province ? $province . ' ' : '';
        $line3 .= $postal ? $postal : '';
        $line3  = rtrim(rtrim($line3, ' '), ',');
        $line3  = strlen($line3) ? $line3 . "\n" : '';
        $line4  = $country;

        // Compile address
        $result = rtrim($line1 . $line2 . $line3 . $line4);

        // (string) Final address
        return $result;
    }

    /**
     * Set a transaction key in session.
     * @param   object  Instance of Transaction.
     * @return  void
     */
    protected function setKey(Transaction $trans)
    {
        // Get transaction ID/key
        if (isset($trans->key) && strlen($trans->key)) {
            $this->transactionKey = $trans->key;
            $_SESSION[self::SESSION_TRANS . $this->publicKey] = $this->transactionKey;
        }
    }

    /**
     * Get previously created transaction key from session.
     * @return  string|bool  FALSE if no transaction key, string otherwise.
     */
    protected function getKey()
    {
        return Vine_Session::get(self::SESSION_TRANS . $this->publicKey);
    }

    /**
     * Parse the line items in a transaction object returned via the API.
     * @param   array  Array of Transaction_lines objects.
     * @return  void
     * @see     parseTransactionResponse()
     */
    private function _parseTransactionLines($lines)
    {
        // Invalid object, stop here
        if ( ! isset($lines[0]) || ! $lines[0] instanceof Transaction_lines) {
            return;
        }

        // Loop through and recompile each transaction line
        foreach ($lines as $line) {
            // This is the shipping and handling line item, don't list in items
            if ('key-shipping' === $line->custom_id) {
                // Compile shipping details
                $this->shipping = number_format((float) $line->amount, 2, '.', '');
                $this->taxes   += $line->tax_amount;
                $this->total   += $line->total_amount;

                // Save this tax name
                if ($line->tax_amount > 0) {
                    if ( ! isset($this->details[$line->tax_name])) {
                        $this->details[$line->tax_name] = $line->tax_amount;
                    } else {
                        $this->details[$line->tax_name] += $line->tax_amount;
                    }
                }

                // Continue to next line item
                continue;
            }

            // Parse the original key/ID (remove 'key-' string)
            $key = substr($line->custom_id, 4);

            // Compile this item
            $this->items[$key] = array
            (
                'key'        => $key,
                'qty'        => (int) $line->quantity,
                'unit_price' => number_format((float) $line->unit_price, 2, '.', ''),
                'subtotal'   => number_format((float) $line->amount, 2, '.', ''),
                'total'      => number_format((float) $line->total_amount, 2, '.', ''),
                'tax_rate'   => number_format((float) $line->tax_rate, 2, '.', ''),
                'tax_amount' => number_format((float) $line->tax_amount, 2, '.', ''),
                'tax_name'   => $line->tax_name,
                'name'       => $line->description,
                'sku'        => $line->product_code,
                'type'       => $line->product_type,
            );

            // Continue calculating order totals
            $this->subtotal += $line->amount;
            $this->taxes    += $line->tax_amount;
            $this->total    += $line->total_amount;

            // Save this tax name
            if ($line->tax_amount > 0) {
                if ( ! isset($this->details[$line->tax_name])) {
                    $this->details[$line->tax_name] = $line->tax_amount;
                } else {
                    $this->details[$line->tax_name] += $line->tax_amount;
                }
            }
        }

        // Standardize numbers
        $this->subtotal = number_format((float) $this->subtotal, 2, '.', '');
        $this->shipping = number_format((float) $this->shipping, 2, '.', '');
        $this->taxes    = number_format((float) $this->taxes, 2, '.', '');
        $this->total    = number_format((float) $this->total, 2, '.', '');

        // Standardize tax details
        if ( ! empty($this->details)) {
            foreach ($this->details as $k => $detail) {
                if ($k === 'Specified') {
                    $this->details['Taxes'] = number_format((float) $detail, 2, '.', '');
                    unset($this->details[$k]);
                } else {
                    $this->details[$k] = number_format((float) $detail, 2, '.', '');
                }
            }
        }
    }

    /**
     * Verify the order totals to make sure all of the numbers match.
     * @param   object  Instance of Transaction.
     * @return  void
     */
    private function _verifyTotals(Transaction $trans)
    {
        // Get totals
        $subtotal = number_format((float) $trans->amount - $this->shipping, 2, '.', '');
        $taxes    = number_format((float) $trans->tax_amount, 2, '.', '');
        $total    = number_format((float) $trans->total_amount, 2, '.', '');

        // Order totals don't match, something went wrong
        if (   $subtotal !== $this->subtotal
            || $taxes    !== $this->taxes
            || $total    !== $this->total
        ) {
            $this->error = self::ERROR_MISMATCH;
        }
    }

    /**
     * Get a Taxamo account's settings.
     * @param   string  The Taxamo account's public key.
     * @return  void
     */
    private function _getSettings($publicKey)
    {
        // Compile URL to settings endpoint
        $url = 'https://dashboard.taxamo.com/app/v1/merchants/self/settings/'
             . 'public?public_token=' . urlencode($publicKey);

        // Execute GET request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        // Parse response
        $data = Vine_Json::decode($result, TRUE);

        // Invalid response, stop here
        if ( ! $data || empty($data)) {
            $this->error = self::ERROR_DEFAULT;
            return;
        }

        // Valid response, invalid public key, stop here
        if (isset($data['error'])) {
            $this->error = $data['error'];
            return;
        // Valid response, invalid public key, stop here
        } elseif (isset($data['errors'][0])) {
            $this->error = $data['errors'][0];
            return;
        }

        // Everything is valid, save settings
        $this->settings = $data['settings'];
    }
}