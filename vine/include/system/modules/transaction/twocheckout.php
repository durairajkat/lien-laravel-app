<?php

/**
 * Wrapper class for the 2Checkout payment system.
 *
 * @author  Tell Konkle
 * @date    2015-09-23
 */
class Transaction_TwoCheckout
{
    /**
     * Buyer redirect URL. 
     */
    const REDIRECT_LIVE = 'https://www.2checkout.com/checkout/purchase';
    const REDIRECT_TEST = 'https://www.2checkout.com/checkout/purchase';

    /**
     * Parameters to use when redirecting buyer to 2CO.
     * @var  array
     * @see  getRedirectUrl()
     */
    protected $goToParams = array
    (
        'sid'  => NULL,
        'mode' => '2CO',
    );

    /**
     * Billing first name and last name. Compiled into single param prior to handoff.
     * @var  string
     * @see  setFirstName()
     * @see  setLastName()
     */
    protected $firstName     = NULL;
    protected $lastName      = NULL;
    protected $shipFirstName = NULL;
    protected $shipLastName  = NULL;

    /**
     * The total number of items passed to 2CO.
     * @var  int
     * @see  setOrderItem()
     * @see  setShippingTotal()
     * @see  setTaxTotal()
     * @see  setDiscountTotal()
     */
    protected $items = 0;

    /**
     * Does this order have any tangible items in it?
     * @var  bool
     */
    protected $hasShipping = FALSE;

    /**
     * Process transaction in test mode?
     * @var  bool
     * @see  setTestMode()
     */
    protected $testMode = FALSE;

    /**
     * Class destructor. Log everything.
     * @return  void
     */
    public function __destruct()
    {
        Vine_Log::logManual($this->goToParams, 'twocheckout.log');
    }

    /**
     * Specify test or production mode.
     * @param   bool  When FALSE, production mode is assumed.
     * @return  void
     */
    public function setTestMode($mode)
    {
        if ($mode) {
            $this->goToParams['demo'] = 'Y';
            $this->testMode = TRUE;
        } else {
            $this->testMode = FALSE;
        }
    }

    /**
     * Set the merchant's 2CO account number.
     * @param   string
     * @return  void
     */
    public function setAccountNumber($number)
    {
        $this->goToParams['sid'] = trim($number);
    }

    /**
     * Set the billing first name.
     * @param   string
     * @return  void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $this->_cleanValue($firstName);
    }

    /**
     * Set the billing last name.
     * @param   string
     * @return  void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $this->_cleanValue($lastName);
    }

    /**
     * Set billing address 1.
     * @param   string
     * @return  void
     */
    public function setAddress1($address)
    {
        $this->goToParams['street_address'] = $this->_cleanValue($address);
    }

    /**
     * Set billing address 2.
     * @param   string
     * @return  void
     */
    public function setAddress2($address)
    {
        if ($address) {
            $this->goToParams['street_address2'] = $this->_cleanValue($address);
        }
    }

    /**
     * Set billing city.
     * @param   string
     * @return  void
     */
    public function setCity($city)
    {
        $this->goToParams['city'] = $this->_cleanValue($city);
    }

    /**
     * Set billing state/province.
     * @param   string
     * @return  void
     */
    public function setProvince($province)
    {
        $this->goToParams['state'] = $this->_cleanValue($province);
    }

    /**
     * Set billing country.
     * @param   string
     * @return  void 
     */
    public function setCountry($country)
    {
        $this->goToParams['country'] = $this->_cleanValue($country);
    }

    /**
     * Set billing zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setPostalCode($postal)
    {
        $this->goToParams['zip'] = $this->_cleanValue($postal);
    }

    /**
     * Set the billing email address.
     * @param   string
     * @return  void
     */
    public function setEmail($email)
    {
        $this->goToParams['email'] = trim($email);
    }

    /**
     * Set the billing phone number.
     * @param   string
     * @return  void
     */
    public function setPhone($phone)
    {
        if ($phone) {
            $this->goToParams['phone'] = $this->_cleanValue($phone);
        }
    }

    /**
     * Set the shipping first name.
     * @param   string
     * @return  void
     */
    public function setShippingFirstName($firstName)
    {
        $this->shipFirstName = $this->_cleanValue($firstName);
    }

    /**
     * Set the shipping last name.
     * @param   string
     * @return  void
     */
    public function setShippingLastName($lastName)
    {
        $this->shipLastName = $this->_cleanValue($lastName);
    }

    /**
     * Set shipping address 1.
     * @param   string
     * @return  void
     */
    public function setShippingAddress1($address)
    {
        $this->goToParams['ship_street_address'] = $this->_cleanValue($address);
    }

    /**
     * Set shipping address 2.
     * @param   string
     * @return  void
     */
    public function setShippingAddress2($address)
    {
        if ($address) {
            $this->goToParams['ship_street_address2'] = $this->_cleanValue($address);
        }
    }

    /**
     * Set shipping city.
     * @param   string
     * @return  void
     */
    public function setShippingCity($city)
    {
        $this->goToParams['ship_city'] = $this->_cleanValue($city);
    }

    /**
     * Set shipping state/province.
     * @param   string
     * @return  void
     */
    public function setShippingProvince($province)
    {
        $this->goToParams['ship_state'] = $this->_cleanValue($province);
    }

    /**
     * Set shipping country.
     * @param   string
     * @return  void 
     */
    public function setShippingCountry($country)
    {
        $this->goToParams['ship_country'] = $this->_cleanValue($country);
    }

    /**
     * Set shipping zip/postal code.
     * @param   int|string
     * @return  void
     */
    public function setShippingPostalCode($postal)
    {
        $this->goToParams['ship_zip'] = $this->_cleanValue($postal);
    }

    /**
     * Set the order/invoice number for the transaction.
     * @param   string
     * @return  void
     */
    public function setOrderNumber($orderNumber)
    {
        $this->goToParams['merchant_order_id'] = trim($orderNumber);
    }

    /**
     * Set the currency code for the transaction.
     * @param   string
     * @return  void
     */
    public function setCurrency($currency)
    {
        $this->goToParams['currency_code'] = trim(strtoupper($currency));
    }

    /**
     * Manually set the purchase step to redirect the buyer to. Must be one of the
     * following values:
     *
     * - review-cart
     * - shipping-information
     * - shipping-method
     * - billing-information
     * - payment-method
     */
    public function setPurchaseStep($step)
    {
        // Valid purchase steps
        $steps = array
        (
            'review-cart',
            'shipping-information',
            'shipping-method',
            'billing-information',
            'payment-method',
        );

        // Only set step if it's valid
        if (in_array($step, $steps)) {
            $this->goToParams['purchase_step'] = $step;
        }
    }

    /**
     * Set the return URL. This is the URL that the buyer will get redirected to once
     * they've completed their purchase at 2CO and choose to go back to the merchant's
     * website.
     *
     * @param   string
     * @return  void
     */
    public function setReturnUrl($url)
    {
        $this->goToParams['x_receipt_link_url'] = $url;
    }

    /**
     * Set a custom paramater to pass to 2CO.
     * @param   string  The parameter name.
     * @param   string  The parameter value.
     * @return  void
     */
    public function setParam($param, $value)
    {
        $this->goToParams[$param] = $value;
    }

    /**
     * Unset a parameter from being sent to 2CO.
     * @param   string  The parameter name.
     * @return  void
     */
    public function unsetParam($param)
    {
        unset($this->goToParams[$param]);
    }

    /**
     * Set a line item (i.e. product) to pass to 2CO.
     *
     * @param   string  Name of the item.
     * @param   int     Quantity of the item.
     * @param   mixed   Price of the item. Must be numeric.
     * @param   bool    Is this a tangible item?
     * @param   string  The purchase option, if any.
     * @return  void
     */
    public function setOrderItem($name, $qty, $price, $tangible, $option = NULL)
    {
        // Compile and add this item
        $item = array
        (
            'li_' . $this->items . '_type'     => 'product',
            'li_' . $this->items . '_name'     => $this->_cleanValue($name),
            'li_' . $this->items . '_quantity' => (int) $qty,
            'li_' . $this->items . '_price'    => number_format($price, 2, '.', ''),
            'li_' . $this->items . '_tangible' => $tangible ? 'Y' : 'N',
        );

        // Specify the chosen purchase option for this product
        if ($option) {
            $item['li_' . $this->items . '_option_value'] = $this->_cleanValue($option);
        }

        // Save product to handoff params
        $this->goToParams = array_merge($item, $this->goToParams);
        
        // Increment total number of items added
        $this->items++;

        // This order is now marked as having at least one tangible item
        if ( ! $this->hasShipping && $tangible) {
            $this->hasShipping = TRUE;
        }
    }

    /**
     * Set the order's shipping total.
     * @param   mixed   Order's shipping total. Must be numeric.
     * @param   string  The name of the shipping method to use.
     * @return  void
     */
    public function setShippingTotal($total, $methodName = 'Standard Shipping')
    {
        // This order does not have any tangible items in it, excluding shipping method
        if ( ! $this->hasShipping) {
            return;
        }

        // Compile and add shipping item
        $item = array
        (
            'li_' . $this->items . '_type'     => 'shipping',
            'li_' . $this->items . '_name'     => $methodName,
            'li_' . $this->items . '_quantity' => 1,
            'li_' . $this->items . '_price'    => number_format($total, 2, '.', ''),
            'li_' . $this->items . '_tangible' => 'Y',
        );

        // Save shipping item to handoff params
        $this->goToParams = array_merge($item, $this->goToParams);
        
        // Increment total number of items added
        $this->items++;
    }

    /**
     * Set the order's tax total.
     * @param  mixed  Order's tax total. Must be numeric.
     * @return  void
     */
    public function setTaxTotal($total)
    {
        // Compile and add tax item
        $item = array
        (
            'li_' . $this->items . '_type'     => 'tax',
            'li_' . $this->items . '_name'     => 'Taxes',
            'li_' . $this->items . '_quantity' => 1,
            'li_' . $this->items . '_price'    => number_format($total, 2, '.', ''),
            'li_' . $this->items . '_tangible' => 'N',
        );

        // Save tax item to handoff params
        $this->goToParams = array_merge($item, $this->goToParams);
        
        // Increment total number of items added
        $this->items++;
    }

    /**
     * Set the order's discount total.
     * @param   mixed   Order's discount total. Must be numeric.
     * @param   string  The order's coupon code, if any.
     * @return  void
     */
    public function setDiscountTotal($total, $couponCode)
    {
        // Only add if discounts are greater than 0 
        if ($total <= 0) {
            return;
        }

        // Compile and add discount item
        $item = array
        (
            'li_' . $this->items . '_type'     => 'coupon',
            'li_' . $this->items . '_name'     => $couponCode ? $couponCode : 'Discounts',
            'li_' . $this->items . '_quantity' => 1,
            'li_' . $this->items . '_price'    => number_format($total, 2, '.', ''),
            'li_' . $this->items . '_tangible' => 'N',
        );

        // Save discount item to handoff params
        $this->goToParams = array_merge($item, $this->goToParams);
        
        // Increment total number of items added
        $this->items++;
    }

    /**
     * Get the URL to use to redirect buyer to 2CO.
     * @return  string
     */
    public function getRedirectUrl()
    {
        // Compile card_holder_name
        if ($this->firstName) {
            $this->goToParams['card_holder_name'] = trim(
                $this->firstName . ' ' . $this->lastName
            );
        }

        // Compile ship_name
        if ($this->shipFirstName) {
            $this->goToParams['ship_name'] = trim(
                $this->shipFirstName . ' ' . $this->shipLastName
            );
        }

        // Automatically parse purchase step to redirect buyer to
        if ( ! isset($this->goToParams['purchase_step'])) {
            $this->goToParams['purchase_step'] = $this->_getPurchaseStep();
        }

        // Get sandbox redirect URL
        if ($this->testMode) {
            return self::REDIRECT_TEST . '?' . http_build_query($this->goToParams);
        // Get production redirect URL
        } else {
            return self::REDIRECT_LIVE . '?' . http_build_query($this->goToParams);
        }
    }

    /**
     * Verify an Instant Payment Notification (IPN).
     * @param   string  The 2CO account ID.
     * @param   string  The secret word for the 2CO account.
     * @param   string  The order total.
     * @return  bool  TRUE if all provided data is valid, FALSE otherwise.
     */
    public function verifyNotification($accountId, $secretWord, $total)
    {
        // User input
        $req   = new Vine_Request();
        $order = $req->input('order_number');
        $key   = $req->input('key') ? $req->input('key') : $req->input('md5_hash');
        $hash  = strtoupper(md5($secretWord . $accountId . $order . $total));
        $valid = $key === $hash;

        // Keep logs of all failed validations
        if ( ! $valid) {
            // Compile log data
            $data = "REQUEST:\n\n"
                  . print_r($req->input(), TRUE) . "\n\n"
                  . "OLD HASH: " . $key . "\n"
                  . "NEW HASH: " . $hash;

            // Put the log into the log directory
            Vine_Log::logManual($data, 'twocheckout-ipn.log');
        }

        // (bool)
        return $valid;
    }

    /**
     * Clean a parameter value to make sure it's compatible with 2CO.
     * @param   mixed
     * @return  mixed
     */
    private function _cleanValue($value)
    {
        return trim(str_replace(array('<', '>'), '', $value));
    }

    /**
     * Automatically parse the purchase step to use when redirecting the buyer to 2CO.
     * @return  string
     */
    private function _getPurchaseStep()
    {
        // Which checkout steps to skip
        $skipShipping = TRUE;
        $skipBilling  = FALSE;

        // Skip the shipping information
        if ($this->hasShipping
            && isset($this->goToParams['ship_street_address'])
            && isset($this->goToParams['ship_city'])
            && isset($this->goToParams['ship_state'])
            && isset($this->goToParams['ship_zip'])
            && isset($this->goToParams['ship_country'])
            && isset($this->goToParams['phone'])
        ) {
            $skipShipping = TRUE;
        // Don't skip shipping information
        } elseif ($this->hasShipping) {
            $skipShipping = FALSE;
        }

        // Skip the shipping information
        if (   isset($this->goToParams['street_address'])
            && isset($this->goToParams['city'])
            && isset($this->goToParams['state'])
            && isset($this->goToParams['zip'])
            && isset($this->goToParams['country'])
            && isset($this->goToParams['email'])
            && isset($this->goToParams['phone'])
        ) {
            $skipBilling = TRUE;
        }

        // Everything is filled out, go directly to payment method step
        if (($skipShipping || ! $this->hasShipping) && $skipBilling) {
            return 'payment-method';
        // Shipping info is filled out or is not needed
        } elseif ($skipShipping || ! $this->hasShipping) {
            return 'billing-information';
         // Shipping info has not be filled out and it's needed
        } elseif ($this->hasShipping) {
            return 'shipping-information';
        // Shipping info has not been filled out but it's not needed
        } else {
            return 'billing-information';
        }
    }
}