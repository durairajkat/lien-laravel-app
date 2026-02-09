<?php

/**
 * Credit card and payment method helper.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Payment
{
    /**
     * Get a database-ready string of the card type based upon the card number entered.
     * ---
     * @param   mixed        The card number to evaluate.
     * @return  string|bool  FALSE if card type could not be found, string otherwise.
     */
    public static function getCardType($number)
    {
        if (self::isVisa($number)) {
            return 'Visa';
        } elseif (self::isAmex($number)) {
            return 'Amex';
        } elseif (self::isDiscover($number)) {
            return 'Discover';
        } elseif (self::isMasterCard($number)) {
            return 'MasterCard';
        } else {
            return FALSE;
        }
    }

    /**
     * Get a receipt-ready, human-readable, string of the payment method used for a
     * specified transaction. If no pay type or pay number is provided, (string) 'None' is
     * returned. If pay type provided but could not be parsed, pay type is returned.
     * ---
     * @param   string  [optional] Payment type by name (e.g "Visa").
     * @param   mixed   [optional] Credit card number or check number.
     * @return  string  Input if payment could not be formatted correctly.
     */
    public static function format($payType = FALSE, $payNumber = FALSE)
    {
        // Standardize input data
        $cleanType   = strtolower(trim($payType));
        $cleanNumber = self::lastFour(Vine_Quick::numberOnly($payNumber));

        // Required data is not present, stop here
        if ( ! strlen($cleanType) && ! strlen($cleanNumber)) {
            return 'None';
        }

        // This was a PayPal transaction
        if (self::isPayPal($cleanType) || self::isPayPal($payNumber)) {
            return 'PayPal';
        }

        // This was a Visa transaction
        if (self::isVisa($cleanType) || self::isVisa($payNumber)) {
            return 'Visa ************' . $cleanNumber;
        }

        // This was an American Express transaction
        if (self::isAmex($cleanType) || self::isAmex($payNumber)) {
            return 'American Express ***********' . $cleanNumber;
        }

        // This was a Discover Card transaction
        if (self::isDiscover($cleanType) || self::isDiscover($payNumber)) {
            return 'Discover Card ************' . $cleanNumber;
        }

        // This was a MasterCard transaction
        if (self::isMasterCard($cleanType) || self::isMasterCard($payNumber)) {
            return 'MasterCard ************' . $cleanNumber;
        }

        // This transaction was paid via check (or "cheque" in the UK)
        if (self::isCheck($cleanType) || self::isCheck($payNumber)) {
            return 'Check' . ($payNumber ? ' #' . $payNumber : '');
        }

        // This transaction was/is paid via COD
        if (self::isCod($cleanType) || self::isCod($payNumber)) {
            return 'C.O.D.';
        }

        // Transaction type could not be parsed correctly, use whatever was provided
        return trim($payType);
    }

    /**
     * Get the last four digits of a specified card number (for PCI compliancy).
     * ---
     * @param   string
     * @return  string
     */
    public static function lastFour($cardNumber)
    {
        return substr($cardNumber, -4);
    }

    /**
     * See if an input value can be attributed to Amex (American Express).
     * ---
     * @param   mixed  Amex type string or credit card number.
     * @return  bool   TRUE if input value can be considered Amex, FALSE otherwise.
     * @see     http://www.regular-expressions.info/creditcard.html
     */
    public static function isAmex($input)
    {
        // Search by name
        if (Vine_Quick::contains($input, array('amex', 'american', 'express'))) {
            return TRUE;
        }

        // Search by card number
        return preg_match('/^3[47][0-9]{13}$/', $input);
    }

    /**
     * See if an input value can be attributed to check.
     * ---
     * @param   string  Check, Cheque, type string.
     * @return  bool    TRUE if input value can be considered check, FALSE otherwise.
     */
    public static function isCheck($input)
    {
        return Vine_Quick::contains($input, array('check', 'cheque', 'ck'));
    }

    /**
     * See if an input value can be attributed to C.O.D. (Certificate of Deposit).
     * ---
     * @param   string  COD type string.
     * @return  bool    TRUE if input value can be considered COD, FALSE otherwise.
     */
    public static function isCod($input)
    {
        return Vine_Quick::contains(Vine_Quick::alphaNumeric($input), 'cod');
    }

    /**
     * See if an input value can be attributed to Discover Card.
     * ---
     * @param   string  Discover type string or credit card number.
     * @return  bool    TRUE if input value can be considered Discover, FALSE otherwise.
     * @see     http://www.regular-expressions.info/creditcard.html
     */
    public static function isDiscover($input)
    {
        // Search by name
        if (Vine_Quick::contains($input, 'disc')) {
            return TRUE;
        }

        // Search by card number
        return preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $input);
    }

    /**
     * See if an input value can be attributed to MasterCard.
     * ---
     * @param   string  MasterCard type string or credit card number.
     * @return  bool    TRUE if input value can be considered MasterCard, FALSE otherwise.
     * @see     http://www.regular-expressions.info/creditcard.html
     */
    public static function isMasterCard($input)
    {
        // Search by name
        if (Vine_Quick::contains($input, array('master', 'mc'))) {
            return TRUE;
        }

        // Search by card number
        return preg_match('/^5[1-5][0-9]{14}$/', $input);
    }

    /**
     * See if an input value can be attributed to PayPal.
     * ---
     * @param   string  PayPal type string.
     * @return  bool    TRUE if input value can be considered PayPal, FALSE otherwise.
     */
    public static function isPayPal($input)
    {
        return Vine_Quick::contains($input, array('PayPal', 'PP'));
    }

    /**
     * See if an input value can be attributed to Visa Card.
     * ---
     * @param   string  Visa type string or credit card number.
     * @return  bool    TRUE if input value can be considered Visa, FALSE otherwise.
     * @see     http://www.regular-expressions.info/creditcard.html
     */
    public static function isVisa($input)
    {
        // Search by name
        if (Vine_Quick::contains($input, 'visa')) {
            return TRUE;
        }

        // Search by card number
        return preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $input);
    }
}
