<?php

// Dependencies
require_once 'stripe/lib/Stripe.php';

/**
 * Wrapper class for the Stripe API.
 * ---
 * @author  Tell Konkle
 * @date    2018-06-18
 */
class Transaction_Stripe
implements Transaction_Interface_Transaction, Transaction_Interface_Billing
{
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
     * An array containing all of the parameters and values to send in the next API
     * operation.
     * ---
     * @var  array
     */
    protected $params = [];

    /**
     * An array containing the last response from the Stripe API.
     * ---
     * @var  array
     */
    protected $lastResponse = [];

    /**
     * ID of the last successful transaction, or ID manually set from a previous preauth
     * transaction.
     * ---
     * @var  mixed
     */
    protected $transactionId = NULL;

    /**
     * ID of the last created customer, or ID manually set from a previously created
     * customer.
     * ---
     * @var  mixed
     */
    protected $customerId = NULL;

    /**
     * Class constructor.
     * ---
     * @param   string
     * @return  void
     */
    public function __construct($apiKey)
    {
        Stripe::setApiKey($apiKey);
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
     * Manually set transaction ID from previous preauth transaction or equivalent.
     * ---
     * @param   string
     * @return  void
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
        $this->params['id']  = $id;
    }

    /**
     * Set the first name on card.
     * ---
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = trim($firstName);
    }

    /**
     * Set the last name on card.
     * ---
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = trim($lastName);
    }

    /**
     * Set the address on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress1($address1)
    {
        $this->params['card']['address_line1'] = trim($address1);
    }

    /**
     * Set the second line of address on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setAddress2($address2)
    {
        if ( ! strlen(trim($address2))) {
            return;
        }

        $this->params['card']['address_line2'] = trim($address2);
    }

    /**
     * Set the city on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->params['card']['address_city'] = trim($city);
    }

    /**
     * Set the state/province on file for card.
     * ---
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->params['card']['address_state'] = trim($province);
    }

    /**
     * Set the country on file for card. Should be a 2-letter ISO 3166-1 country code.
     * ---
     * @param   string
     * @return  void
     */
    public function setCountry($country)
    {
        $this->params['card']['address_country'] = trim($country);
    }

    /**
     * Set the zip/postal code on file for card.
     * ---
     * @param   string|int
     * @return  void
     */
    public function setPostalCode($code)
    {
        $this->params['card']['address_zip'] = trim($code);
    }

    /**
     * Set the card number.
     * ---
     * @param   int|float|string
     * @return  void
     */
    public function setCardNumber($number)
    {
        $this->params['card']['number'] = preg_replace('/[^0-9]/', '', $number);
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
        $this->params['card']['exp_month'] = (int) $month;
        $this->params['card']['exp_year']  = (int) substr($year , (strlen($year) - 2), 2);
    }

    /**
     * Set the card's security code.
     * ---
     * @param   string|int
     * @return  void
     */
    public function setSecurityCode($cvv)
    {
        $this->params['card']['cvc'] = trim($cvv);
    }

    /**
     * Set the currency code for the transaction.
     * ---
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->params['currency'] = $currency;
    }

    /**
     * Set customer's email address.
     * ---
     * @param   string  The email address.
     * @return  void
     */
    public function setEmail($email)
    {
        if (Vine_Verify::email($email)) {
            $this->params['email'] = $email;
        }
    }

    /**
     * Set a custom parameter to the API.
     * ---
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
     * ---
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doPreAuth($amount)
    {
        // Convert amount to cents
        $amount = str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['card']['name'] = $this->firstName . ' ' . $this->lastName;
        $this->params['amount']       = (int) $amount;
        $this->params['capture']      = FALSE;

        // Automatically set currency if it's not set already
        if ( ! isset($this->params['currency'])) {
            $this->params['currency'] = 'USD';
        }

        // (bool) Process the transaction
        return $this->processCharge('create', $this->params);
    }

    /**
     * Do a capture on a prior authorization.
     * ---
     * [!!!] The authorization transaction ID must already be set.
     * ---
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     */
    public function doPostAuth($amount)
    {
        // Convert amount to cents
        $amount = (int) str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['amount'] = (int) $amount;

        // (bool) Process the transaction
        return $this->processCharge('capture', $this->params);
    }

    /**
     * Do an authorization and capture (sale).
     * ---
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doSale($amount)
    {
        // Convert amount to cents
        $amount = str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['card']['name'] = $this->firstName . ' ' . $this->lastName;
        $this->params['amount']       = (int) $amount;
        $this->params['capture']      = TRUE;

        // Automatically set currency if it's not set already
        if ( ! isset($this->params['currency'])) {
            $this->params['currency'] = 'USD';
        }

        // (bool) Process the transaction
        return $this->processCharge('create', $this->params);
    }

    /**
     * Do a credit to a previous transaction.
     * ---
     * [!!!] The transaction ID must already be set.
     * ---
     * @param   string|float  The credit amount.
     * @return  bool          TRUE if credit successful, FALSE otherwise.
     */
    public function doCredit($amount)
    {
        // Convert amount to cents
        $amount = (int) str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['amount'] = (int) $amount;

        // (bool) Process the transaction
        return $this->processCharge('refund', $this->params);
    }

    /**
     * Get the customer ID.
     * ---
     * @return  string|int  NULL if no customer ID present.
     */
    public function getBillingId()
    {
        return $this->customerId;
    }

    /**
     * Set a customer ID.
     * ---
     * @param   string|int
     * @return  void
     */
    public function setBillingId($billingId)
    {
        $this->customerId         = $billingId;
        $this->params['id']       = $billingId;
        $this->params['customer'] = $billingId;
    }

    /**
     * Create a customer ID.
     * ---
     * @return  bool  TRUE if customer ID created successfully, FALSE otherwise.
     */
    public function doBillingCreate()
    {
        // Remove unneeded params
        unset($this->params['amount']);
        unset($this->params['capture']);
        unset($this->params['currency']);

        // Create new customer
        $this->params['card']['name'] = $this->firstName . ' ' . $this->lastName;
        return $this->processCustomer('create', $this->params);
    }

    /**
     * Delete a customer ID.
     * ---
     * [!!!] The customer ID must already be set with setBillingId().
     * ---
     * @return  bool  TRUE if customer ID deleted successfully, FALSE otherwise.
     */
    public function doBillingDelete()
    {
        // Billing ID is required to do a billing delete
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to delete billing profile. Billing ID not loaded.');
            return FALSE;
        }

        // Remove unneeded params
        unset($this->params['customer']);

        // Process billing delete
        return $this->processCustomer('delete', $this->params);
    }

    /**
     * Update a customer ID.
     * ---
     * [!!!] The customer ID must already be set with setBillingId().
     * ---
     * @return  bool  TRUE if customer ID updated successfully, FALSE otherwise.
     */
    public function doBillingUpdate()
    {
        // Billing ID is required to do billing update
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete billing update. Missing billing ID.');
            return FALSE;
        }

        // Remove unneeded params
        unset($this->params['customer']);

        // Process billing update
        $this->params['card']['name'] = $this->firstName . ' ' . $this->lastName;
        return $this->processCustomer('save', $this->params);
    }

    /**
     * Do a pre-authorization transaction using a customer ID.
     * ---
     * [!!!] The customer ID must already be set with setBillingId().
     * ---
     * @param   string|int|float  The authorization amount.
     * @return  bool              TRUE if authorization successful, FALSE otherwise.
     */
    public function doBillingPreAuth($amount)
    {
        // Billing ID is required to do an authorization
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete authorization. Missing billing ID.');
            return FALSE;
        }

        // Convert amount to cents
        $amount = str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Remove unneeded params
        unset($this->params['id']);

        // Automatic data preparation
        $this->params['amount']  = (int) $amount;
        $this->params['capture'] = FALSE;

        // Automatically set currency if it's not set already
        if ( ! isset($this->params['currency'])) {
            $this->params['currency'] = 'USD';
        }

        // (bool) Process the transaction
        return $this->processCharge('create', $this->params);
    }

    /**
     * Do a capture against an authorization that was made using a customer ID.
     * ---
     * [!!!] The authorization transaction ID must already be set.
     * ---
     * @param   string|int|float  The capture amount.
     * @return  bool              TRUE if capture successful, FALSE otherwise.
     */
    public function doBillingPostAuth($amount)
    {
        // Transaction ID is required to do a capture on a prior authorization
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete capture. Missing Transaction ID.');
            return FALSE;
        }

        // Convert amount to cents
        $amount = (int) str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['amount'] = (int) $amount;

        // (bool) Process the transaction
        return $this->processCharge('capture', $this->params);
    }

    /**
     * Do an authorization and capture (sale) using a customer ID.
     * ---
     * [!!!] The customer ID must already be set with setBillingId().
     * ---
     * @param   string|int|float  The transaction amount.
     * @return  bool              TRUE if sale successful, FALSE otherwise.
     */
    public function doBillingSale($amount)
    {
        // Billing ID is required to do a sale
        if (NULL === $this->getBillingId()) {
            $this->setError('Unable to complete sale. Missing billing ID.');
            return FALSE;
        }

        // Convert amount to cents
        $amount = str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Remove unneeded params
        unset($this->params['id']);

        // Automatic data preparation
        $this->params['amount']  = (int) $amount;
        $this->params['capture'] = TRUE;

        // Automatically set currency if it's not set already
        if ( ! isset($this->params['currency'])) {
            $this->params['currency'] = 'USD';
        }

        // (bool) Process the transaction
        return $this->processCharge('create', $this->params);
    }

    /**
     * Do a credit to a previous transaction using a customer ID.
     * ---
     * [!!!] The authorization transaction ID must already be set.
     * ---
     * @param   string|int|float  The credit amount.
     * @return  bool              TRUE if credit successful, FALSE otherwise.
     */
    public function doBillingCredit($amount)
    {
        // Transaction ID is required to do a credit on a prior transaction
        if (NULL === $this->getTransactionId()) {
            $this->setError('Unable to complete credit. Missing Transaction ID.');
            return FALSE;
        }

        // Convert amount to cents
        $amount = (int) str_replace('.', '', number_format((float) $amount, 2, '.', ''));

        // Automatic data preparation
        $this->params['amount'] = (int) $amount;

        // (bool) Process the transaction
        return $this->processCharge('refund', $this->params);
    }

    /**
     * Process a Stripe API Charge method.
     * ---
     * @param   string  Method: 'create', 'retrieve', 'refund', 'capture'
     * @param   mixed
     * @return  bool
     */
    protected function processCharge($method, $param)
    {
        try {
            switch ($method) {
                // Create a new charge (capture now, or capture later)
                case 'create':
                    $this->parseChargeResponse(Stripe_Charge::create($param));
                    return TRUE;
                    break;
                // Retrieve an existing charge
                case 'retrieve':
                    $this->parseChargeResponse(Stripe_Charge::retrieve($param['id']));
                    return TRUE;
                    break;
                // Refund an existing charge
                case 'refund':
                    $stripe = Stripe_Charge::retrieve($param['id']);
                    $stripe->refund(array('amount' => $param['amount']));
                    $this->parseChargeResponse($stripe);
                    return TRUE;
                    break;
                // Capture a previously uncaptured charge
                case 'capture':
                    $stripe = Stripe_Charge::retrieve($param['id']);
                    $stripe->capture(array('amount' => $param['amount']));
                    $this->parseChargeResponse($stripe);
                    return TRUE;
                    break;
                // Invalid charge method
                default:
                    throw new LogicException('Invalid charge method.');
                    return FALSE;
                break;
            }
        // Card was declined
        } catch (Stripe_CardError $e) {
            $this->setError($e->getMessage());
            return FALSE;
        // Network problem
        } catch (Stripe_ApiConnectionError $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        // Programming/logic error, must fix
        } catch (Stripe_InvalidRequestError $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        // Stripe's servers are down
        } catch (Stripe_ApiError $e) {
            $this->setError();
            Vine_Log::logException($e);
            return FALSE;
        // Something else went wrong
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        }
    }

    /**
     * Process a Stripe API Customer method.
     * ---
     * @param   string  Method: 'create', 'save', 'delete'
     * @param   mixed
     * @return  bool
     */
    protected function processCustomer($method, $param)
    {
        try {
            switch ($method) {
                // Create a new customer
                case 'create':
                    $this->parseCustomerResponse(Stripe_Customer::create($param));
                    return TRUE;
                    break;
                // Update an existing customer
                case 'save':
                    // Get existing customer
                    $customer = Stripe_Customer::retrieve($param['id']);

                    // Set new customer details
                    if (is_array($param) && ! empty($param)) {
                        foreach ($param as $key => $value) {
                            $customer->$key = $value;
                        }
                    }

                    // Save changes
                    $customer->save();
                    return TRUE;
                    break;
                // Delete an existing customer
                case 'delete':
                    $customer = Stripe_Customer::retrieve($param['id']);
                    $customer->delete();
                    return TRUE;
                    break;
                // Invalid customer method
                default:
                    throw new LogicException('Invalid customer method.');
                    return FALSE;
                break;
            }
        // Card was declined
        } catch (Stripe_CardError $e) {
            $this->setError($e->getMessage());
            return FALSE;
        // Network problem
        } catch (Stripe_ApiConnectionError $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        // Programming/logic error, must fix
        } catch (Stripe_InvalidRequestError $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        // Stripe's servers are down
        } catch (Stripe_ApiError $e) {
            $this->setError();
            Vine_Log::logException($e);
            return FALSE;
        // Something else went wrong
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Vine_Log::logException($e);
            return FALSE;
        }
    }

    /**
     * Parse a Stripe API charge response object.
     * ---
     * @param   object  Instance of Stripe_Charge.
     * @return  void
     */
    protected function parseChargeResponse(Stripe_Charge $response)
    {
        $this->transactionId = $response->id;
    }

    /**
     * Parse a Stripe API customer response object.
     * ---
     * @param   object  Instance of Stripe_Customer.
     * @return  void
     */
    protected function parseCustomerResponse(Stripe_Customer $response)
    {
        $this->customerId = $response->id;
    }
}