<?php

// Dependencies
require_once 'authnet-sdk/AuthorizeNet.php';

/**
 * Wrapper class of the AIM and CIM API libraries for Authorize.Net.
 *
 * @author  Tell Konkle
 * @date    2015-04-01
 */
class Transaction_AuthNet
implements Transaction_Interface_Transaction, Transaction_Interface_Billing
{
    /**
     * API access credentials.
     * @var  string
     * @see  __construct()
     */
    protected $merchantId     = NULL;
    protected $transactionKey = NULL;

    /**
     * Instance of AuthorizeNetAIM.
     * @var  object
     * @see  __construct()
     */
    protected $aim = NULL;

    /**
     * Instance of AuthorizeNetCIM.
     * @var  object
     * @see  __construct()
     */
    protected $cim = NULL;

    /**
     * Instance of AuthorizeNetCustomer (for CIM).
     * @var  object
     * @see  __construct()
     */
    protected $customer = NULL;

    /**
     * Human-readable error message.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * ID of the last successful transaction, or ID manually set from a previous preauth
     * transaction.
     *
     * @var  string
     * @see  setTransactionId()
     * @see  getTransactionId()
     */
    protected $transactionId = NULL;

    /**
     * Class constructor. Set API access credentials for Authorize.Net AIM/CIM APIs.
     * Enable/disable test mode transactions.
     *
     * @param   string
     * @param   string
     * @param   bool
     * @return  void
     */
    public function __construct($merchantId, $transactionKey, $testMode = FALSE)
    {
        // API credentials
        $this->merchantId     = $merchantId;
        $this->transactionKey = $transactionKey;

        // Disable log file
        if ( ! defined('AUTHORIZENET_LOG_FILE')) {
            define('AUTHORIZENET_LOG_FILE', FALSE);
        }

        // Define test or production mode
        if ( ! defined('AUTHORIZENET_SANDBOX')) {
            define('AUTHORIZENET_SANDBOX', (bool) $testMode);
        }

        // Initialize AIM and CIM wrappers
        $this->aim = new AuthorizeNetAIM($merchantId, $transactionKey);
        $this->cim = new AuthorizeNetCIM($merchantId, $transactionKey);
        $this->aim->VERIFY_PEER = FALSE;
        $this->cim->VERIFY_PEER = FALSE;

        // Initialize CIM customer and payment profile
        $this->customer = new AuthorizeNetCustomer();
        $this->customer->paymentProfiles[0] = new AuthorizeNetPaymentProfile();
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
     * Set the first name on card.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->aim->first_name = trim($firstName);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->firstName = trim($firstName);
    }

    /**
     * Set the last name on card.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->aim->last_name = trim($lastName);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->lastName = trim($lastName);
    }

    /**
     * Set the company name on file for card.
     * @param  string
     * @return  void
     */
    public function setCompany($company)
    {
        if ( ! strlen(trim($company))) {
            return;
        }

        $this->aim->company = trim($company);
    }

    /**
     * Set the address on file for card.
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->aim->address = trim($address1);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->address = trim($address1);
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

        $this->aim->setCustomField('address2', trim($address2));
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->address .= ' ' . trim($address2);
    }

    /**
     * Set the city on file for card.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->aim->city = trim($city);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->city = trim($city);
    }

    /**
     * Set the state/province on file for card.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->aim->state = trim($province);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->state = trim($province);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->aim->country = trim($country);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->country = trim($country);
    }

    /**
     * Set the zip/postal code on file for card.
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->aim->zip = trim($code);
        $this->customer
             ->paymentProfiles[0]
             ->billTo
             ->zip = trim($code);
    }

    /**
     * Set the customer's phone number.
     * @param   string
     * @return  void
     */
    public function setPhone($phone)
    {
        // Sanitize phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Valid phone number
        if ($phone) {
            $this->aim->phone = $phone;
        // Invalid phone number, use filler (some merchant accounts require this field)
        } else {
            $this->aim->phone = '5555555555';
        }
    }

    /**
     * Set the customer's email address.
     * @param   string  Any valid email address.
     * @return  void
     */
    public function setEmail($email)
    {
        $this->aim->email      = trim($email);
        $this->customer->email = trim($email);
    }

    /**
     * Set the card number.
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->aim->card_num = preg_replace('/[^0-9]/', '', $number);
        $this->customer
             ->paymentProfiles[0]
             ->payment
             ->creditCard
             ->cardNumber = preg_replace('/[^0-9]/', '', $number);
    }

    /**
     * Set the card's expiration date.
     * @param   string|int  Expiration month. Must be two digits (01-12).
     * @param   string|int  Expiration year. Must be four digits (2014).
     * @return  void
     */
    public function setExpirationDate($month, $year)
    {
        $this->aim->exp_date = $month . '/' . $year;
        $this->customer
             ->paymentProfiles[0]
             ->payment
             ->creditCard
             ->expirationDate = $year . '-' . $month;
    }

    /**
     * Set the card's security code.
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->aim->card_code = trim($cvv);
        $this->customer
             ->paymentProfiles[0]
             ->payment
             ->creditCard
             ->cardCode = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     *
     * [!!!] Authorize.Net currently only supports USD currency.
     *
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        try {
            // Do nothing (Authorize.Net defaults to USD)
            if ('USD' === $currency) {
                return;
            }

            // Developer warning
            throw new VineBadValueException('Authorize.Net does not support '
                    . 'the ' . $currency . ' currency. USD is the only supported '
                    . 'currency.');
        } catch (VineBadValueException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Set the order/invoice number for the transaction.
     * @param   string
     * @return  void
     */
    public function setOrderNumber($orderNumber)
    {
        $this->aim->invoice_num = trim($orderNumber);
    }

    /**
     * Set a custom paramater to AIM API. Not applicable to CIM API.
     * @param   string  The parameter name.
     * @param   string  The parameter value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        $this->aim->setCustomField($param, $value);
    }

    /**
     * Do a pre-authorization transaction using the AIM API.
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        $this->aim->method            = 'CC';
        $this->aim->recurring_billing = 'NO';
        $this->aim->description       = 'Online Transaction';
        $this->aim->customer_ip       = Vine_Request::getIp();
        $this->aim->amount            = number_format((float) $amount, 2, '.', '');
        return $this->_parseAimResponse($this->aim->authorizeOnly());
    }

    /**
     * Do a capture on a prior authorization using the AIM API.
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
        $this->aim->amount   = number_format((float) $amount, 2, '.', '');
        $this->aim->trans_id = $this->getTransactionId();
        return $this->_parseAimResponse($this->aim->priorAuthCapture());
    }

    /**
     * Do an authorization and capture (sale) using the AIM API.
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        $this->aim->method            = 'CC';
        $this->aim->recurring_billing = 'NO';
        $this->aim->description       = 'Online Transaction';
        $this->aim->customer_ip       = Vine_Request::getIp();
        $this->aim->amount            = number_format((float) $amount, 2, '.', '');
        return $this->_parseAimResponse($this->aim->authorizeAndCapture());
    }

    /**
     * Do a credit to a previous transaction using the AIM API.
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
        $this->aim->amount   = number_format((float) $amount, 2, '.', '');
        $this->aim->trans_id = $this->getTransactionId();
        return $this->_parseAimResponse($this->aim->credit());
    }

    /**
     * Get customer/billing ID of the applicable customer/payment profile.
     * @return  string
     */
    public function getBillingId()
    {
        return $this->customer->customerProfileId;
    }

    /**
     * Set the customer/billing ID and load the applicable customer/payment profile using
     * the CIM API.
     *
     * @param    string
     * @return   bool    FALSE if customer/payment profile failed to load.
     */
    public function setBillingId($billingId)
    {
        // Silently ignore duplicate requests
        if ( ! empty($this->customer->customerProfileId)
            && $billingId === $this->customer->customerProfileId
        ) {
            return TRUE;
        }

        // Don't allow conflicing loading of customer profile IDs
        if ( ! empty($this->customer->customerProfileId)
            && $billingId !== $this->customer->customerProfileId
        ) {
            $this->setError('Billing and customer profile already loaded.');
            return FALSE;
        }

        // Load customer's profile and billing profile
        $response = $this->cim->getCustomerProfile($billingId);

        // Profile found, now load it
        if ($response->isOk()) {
            // Set the customer profile ID
            $this->customer
                 ->customerProfileId = $response->getCustomerProfileId();

            // Set the payment profile ID
            $this->customer
                 ->paymentProfiles[0]
                 ->customerPaymentProfileId = $response->getPaymentProfileId();

            // Parse customer profile info
            $profile     = $response->xml->profile->asXml();
            $description = $this->_getXmlContents($profile, 'description');
            $email       = $this->_getXmlContents($profile, 'email');
            $customerId  = $this->_getXmlContents($profile, 'merchantCustomerId');

            // Set customer profile info
            $this->setEmail($email);
            $this->setBillingCustomerDescription($description);
            $this->setBillingMerchantCustomerId($customerId);

            // Successful
            return TRUE;
        }

        // Failed to load profile
        $this->setError($response->getErrorMessage());
        return FALSE;
    }

    /**
     * Attempt to create a customer/payment profile using the CIM API.
     * @return  bool  TRUE if customer/payment profile created, FALSE otherwise.
     */
    public function doBillingCreate()
    {
        // Attempt to create customer/payment profile
        $response = $this->cim->createCustomerProfile($this->customer);

        // Successfully created customer/payment profile
        if ($response->isOk()) {
            return $this->setBillingId($response->getCustomerProfileId());
        }

        // Failed to create customer/payment profile
        $this->setError($response->getErrorMessage());
        return FALSE;
    }

    /**
     * Attempt to delete a customer/payment profile using the CIM API.
     *
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @return  bool  TRUE if customer/payment profile deleted, FALSE otherwise.
     */
    public function doBillingDelete()
    {
        // Customer profile must be loaded to do a CIM delete
        if ( ! $this->customer->customerProfileId) {
            $this->setError('Unable to delete billing profile. Billing ID not loaded.');
            return FALSE;
        }

        // Attempt to delete customer/payment profile
        $response = $this->cim->deleteCustomerProfile($this->customer->customerProfileId);

        // Successfully deleted customer/payment profile
        if ($response->isOk()) {
            return TRUE;
        }

        // Failed to delete customer/payment profile
        $this->setError($response->getErrorMessage());
        return FALSE;
    }

    /**
     * Attempt to update a customer/payment profile using the CIM API.
     *
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @return  bool  TRUE if customer/payment profile updated, FALSE otherwise.
     */
    public function doBillingUpdate()
    {
        // Customer ID, payment profile ID, payment profile
        $cust    = $this->customer->customerProfileId;
        $pay     = $this->customer->paymentProfiles[0]->customerPaymentProfileId;
        $profile = $this->customer->paymentProfiles[0];

        // Customer profile must be loaded to do a CIM update
        if ( ! $cust) {
            $this->setError('Unable to complete billing update. Billing ID not loaded.');
            return FALSE;
        }

        // Attempt to update payment profile
        $response = $this->cim->updateCustomerPaymentProfile($cust, $pay, $profile);

        // Successfully updated payment profile
        if ($response->isOk()) {
            $this->setBillingId($cust);
            return TRUE;
        }

        // Failed to update payment profile
        $this->setError($response->getErrorMessage());
        return FALSE;
    }

    /**
     * Do a pre-authorization transaction using the CIM API.
     *
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingPreAuth($amount)
    {
        // Get customer ID and payment profile ID, sanitize amount
        $cust   = $this->customer->customerProfileId;
        $pay    = $this->customer->paymentProfiles[0]->customerPaymentProfileId;
        $amount = number_format((float) $amount, 2, '.', '');

        // Customer profile must be loaded to do a pre-authorization
        if ( ! $cust) {
            $this->setError('Unable to complete authorization. Billing ID not loaded.');
            return FALSE;
        }

        // Payment profile must be loaded to do a pre-authorization
        if ( ! $pay) {
            $this->setError('Unable to complete authorization. Billing ID not loaded.');
            return FALSE;
        }

        // Compile authorization transaction
        $auth = new AuthorizeNetTransaction();
        $auth->customerProfileId        = $cust;
        $auth->customerPaymentProfileId = $pay;
        $auth->amount                   = $amount;

        // Attempt authorization transaction
        $response = $this->cim->createCustomerProfileTransaction('AuthOnly', $auth);
        return $this->_parseCimResponse($response);
    }

    /**
     * Do a capture on a prior authorization using the CIM API.
     *
     * [!!!] The authorization transaction ID must already be set.
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setBillingId()
     */
    public function doBillingPostAuth($amount)
    {
        // Get customer ID and payment profile ID, sanitize amount
        $cust   = $this->customer->customerProfileId;
        $pay    = $this->customer->paymentProfiles[0]->customerPaymentProfileId;
        $amount = number_format((float) $amount, 2, '.', '');

        // Customer profile must be loaded to do a capture on a prior authorization
        if ( ! $cust) {
            $this->setError('Unable to complete capture. Billing ID not loaded.');
            return FALSE;
        }

        // Payment profile must be loaded to do a capture on a prior authorization
        if ( ! $pay) {
            $this->setError('Unable to complete capture. Billing ID not loaded.');
            return FALSE;
        }

        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Compile capture transaction
        $capture = new AuthorizeNetTransaction();
        $capture->customerProfileId = $cust;
        $capture->transId           = $this->getTransactionId();
        $capture->amount            = $amount;

        // Attempt capture transaction
        $response = $this->cim->createCustomerProfileTransaction('PriorAuthCapture', $capture);
        return $this->_parseCimResponse($response);
    }

    /**
     * Do an authorization and capture (sale) using the CIM API.
     *
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     * @see     setBillingId()
     */
    public function doBillingSale($amount)
    {
        // Get customer ID and payment profile ID, sanitize amount
        $cust   = $this->customer->customerProfileId;
        $pay    = $this->customer->paymentProfiles[0]->customerPaymentProfileId;
        $amount = number_format((float) $amount, 2, '.', '');

        // Customer profile must be loaded prior to doing a sale
        if ( ! $cust) {
            $this->setError('Unable to complete sale. Billing ID not loaded.');
            return FALSE;
        }

        // Payment profile must be loaded prior to doing a sale
        if ( ! $pay) {
            $this->setError('Unable to complete sale. Billing ID not loaded.');
            return FALSE;
        }

        // Compile sale transaction
        $sale = new AuthorizeNetTransaction();
        $sale->customerProfileId        = $cust;
        $sale->customerPaymentProfileId = $pay;
        $sale->amount                   = $amount;

        // Attempt sale transaction
        $response = $this->cim->createCustomerProfileTransaction('AuthCapture', $sale);
        return $this->_parseCimResponse($response);
    }

    /**
     * Do a credit to a previous transaction using the CIM API.
     *
     * [!!!] The authorization transaction ID must already be set.
     * [!!!] The customer/payment profile must already be loaded with setBillingId().
     *
     * @param   string|int|float  The credit amount.
     * @return  bool              TRUE if credit successful, FALSE otherwise.
     * @see     setTransactionId()
     * @see     setBillingId()
     */
    public function doBillingCredit($amount)
    {
        // Get customer ID and payment profile ID, sanitize amount
        $cust   = $this->customer->customerProfileId;
        $pay    = $this->customer->paymentProfiles[0]->customerPaymentProfileId;
        $amount = number_format((float) $amount, 2, '.', '');

        // Customer profile must be loaded to do a credit on a previous transaction
        if ( ! $cust) {
            $this->setError('Unable to complete credit. Billing ID not loaded.');
            return FALSE;
        }

        // Payment profile must be loaded to do a credit on a previous transaction
        if ( ! $pay) {
            $this->setError('Unable to complete credit. Billing ID not loaded.');
            return FALSE;
        }

        // Transaction ID is required to do a credit on a previous transaction
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Compile credit transaction
        $credit = new AuthorizeNetTransaction();
        $credit->customerProfileId        = $cust;
        $credit->customerPaymentProfileId = $pay;
        $credit->transId                  = $this->getTransactionId();
        $credit->amount                   = $amount;

        // Attempt credit transaction
        $response = $this->cim->createCustomerProfileTransaction('Refund', $credit);
        return $this->_parseCimResponse($response);
    }

    /**
     * Set a unique custom identifier, such as an account ID, for the loaded
     * customer/payment profile.
     *
     * @param   string|int|float
     * @return  void
     */
    public function setBillingMerchantCustomerId($customerId)
    {
        $this->customer->merchantCustomerId = $customerId;
    }

    /**
     * Set the description for the loaded customer/payment profile.
     * @param   string
     * @return  void
     */
    public function setBillingCustomerDescription($description)
    {
        $this->customer->description = $description;
    }

    /**
     * Parse a response from the Authorize.Net AIM API.
     * @param   object  Instance of AuthorizeNetAIM_Response.
     * @return  bool
     */
    private function _parseAimResponse(AuthorizeNetAIM_Response $response)
    {
        // Transaction successful
        if ($response->approved) {
            $this->setTransactionId($response->transaction_id);
            return TRUE;
        // Transaction contains errors
        } elseif ($response->error) {
            $this->setError($response->error_message);
            return FALSE;
        // Transaction declined
        } elseif ($response->declined) {
            $this->setError('The transaction has been declined.');
            return FALSE;
        // API failed
        } else {
            $this->setError('The transaction could not be processed.');
            return FALSE;
        }
    }

    /**
     * Parse a response from the Authorize.Net CIM API.
     * @param   object  Instance of AuthorizeNetCIM_Response.
     * @return  bool
     */
    private function _parseCimResponse(AuthorizeNetCIM_Response $response)
    {
        // Request successful
        if ($response->isOk()) {
            $transaction = $response->getTransactionResponse();
            $this->setTransactionId($transaction->transaction_id);
            return TRUE;
        }

        // Request failed
        $this->setError($response->getErrorMessage());
        return FALSE;
    }

    /**
     * Get the value of a specific XML element in an XML string.
     * @param   string  The XML source string.
     * @param   string  The XML element name.
     * @return  mixed   FALSE if element not found.
     */
    private function _getXmlContents($source, $param)
    {
        $start = '<' . $param . '>';
        $end   = '</' . $param . '>';

        if (FALSE === strpos($source, $start) || FALSE === strpos($source, $end)) {
            return FALSE;
        }

        $startPosition = strpos($source, $start) + strlen($start);
        $endPosition   = strpos($source, $end);
        return substr($source, $startPosition, $endPosition - $startPosition);
    }
}