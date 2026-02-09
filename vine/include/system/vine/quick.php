<?php

/**
 * Quick helper functions.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Quick
{
    /**
     * Convert a mixed value to a boolean.
     * ---
     * @param   mixed
     * @return  bool
     */
    public static function toBool($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Quickly format a monetary value with commas.
     * ---
     * @param   mixed   The monetary value.
     * @return  string  The monetary value in '1,234.56' (string) format.
     */
    public static function money($input)
    {
        if ($input < 0) {
            return number_format(0 - abs($input), 2, '.', ',');
        } else {
            return number_format((float) $input, 2, '.', ',');
        }
    }

    /**
     * Quickly format a monetary value without commas.
     * ---
     * @param   mixed   The monetary value.
     * @return  string  The monetary value in '1234.56' (string) format.
     */
    public static function moneyRaw($input)
    {
        if ($input < 0) {
            return number_format(0 - abs($input), 2, '.', '');
        } else {
            return number_format((float) $input, 2, '.', '');
        }
    }

    /**
     * Quickly format a date.
     * ---
     * @param   mixed   The date. An strlen() compatible string, or unix timestamp.
     * @return  string  Date formatted as 'September 27, 2010 - 11:58 AM' (string).
     */
    public static function dateFullLong($input)
    {
        return Vine_Date::show('F j, Y - g:i A', $input);
    }

    /**
     * Quickly format a date.
     * ---
     * @param   mixed   The date. An strlen() compatible string, or unix timestamp.
     * @return  string  Date formatted as '2010-09-27, 11:58 AM' (string).
     */
    public static function dateFullShort($input)
    {
        return Vine_Date::show('Y-m-d, g:i A', $input);
    }

    /**
     * Quickly format a date.
     * ---
     * @param   mixed   The date. An strlen() compatible string, or unix timestamp.
     * @return  string  Date formatted as 'September 27, 2010' (string).
     */
    public static function dateOnlyLong($input)
    {
        return Vine_Date::show('F j, Y', $input);
    }

    /**
     * Quickly format a date.
     * ---
     * @param   mixed   The date. An strlen() compatible string, or unix timestamp.
     * @return  string  Date formatted as '2010-09-27' (string).
     */
    public static function dateOnlyShort($input)
    {
        return Vine_Date::show('Y-m-d', $input);
    }

    /**
     * Remove every character from input string except numbers.
     * ---
     * @param   mixed   The input string or number.
     * @return  string  Only numbers, or empty string.
     */
    public static function numberOnly($input)
    {
        return preg_replace('/\D/', '', $input);
    }

    /**
     * Remove all characters from string that are not ASCII letters or numbers.
     * ---
     * @param   string  The string to convert.
     * @param   bool    [optional] Allow spaces? Defaults to FALSE.
     * @return  string  An alphanumeric string.
     */
    public static function alphaNumeric($input, $spaces = FALSE)
    {
        if ( ! $spaces) {
            return preg_replace('/[^A-Za-z0-9]/', '', $input);
        } else {
            return preg_replace('/[^A-Za-z0-9 ]/', '', $input);
        }
    }

    /**
     * See if a string or array contains a specified string.
     * ---
     * [!!!] This function is recursive when an array is provided to search in.
     * ---
     * @param   string|array  The string or array to search in.
     * @param   string        The string or array of strings to search for.
     * @param   bool          [optional] Case sensitive search? Defaults to FALSE.
     * @param   bool          [optional] Search in array keys, too? Defaults to FALSE.
     * @return  bool          FALSE if string to search for not found, TRUE otherwise.
     */
    public static function contains($haystack, $needle, $case = FALSE, $keys = FALSE)
    {
        // Haystack is array, search in all values
        if (is_array($haystack) && ! empty($haystack)) {
            // (recursion) Loop through all values and check
            foreach ($haystack as $key => $value) {
                // Stop here if needle found in array value
                if (self::contains($value, $needle, $case, $keys)) {
                    return TRUE;
                // Stop here if needle found in array's key and is allowed to look there
                } elseif ($keys && self::contains($key, $needle, $case, $keys)) {
                    return TRUE;
                }
            }
        // Haystack is string|int|float
        } elseif ( ! is_object($haystack) && ! is_array($haystack)) {
            // Needle is an array of values to look for
            if (is_array($needle) && ! empty($needle)) {
                // Loop through needle and see if any of the values appear in haystack
                foreach ($needle as $check) {
                    if (self::contains($haystack, $check, $case, $keys)) {
                        return TRUE;
                    }
                }
            // Needle is a string, int, or float to look for
            } elseif ( ! is_array($needle) && ! is_object($needle)) {
                return $case
                    ? FALSE !== strpos($haystack, $needle)
                    : FALSE !== stripos($haystack, $needle);
            }
        }

        // The needle was not found in the haystack
        return FALSE;
    }

    /**
     * Prepare current IP address for database storage.
     * ---
     * @param   mixed  [optional] IP address to store. If empty, current IP is used.
     * @return  string
     */
    public static function storeIp($ip = NULL)
    {
        return inet_pton($ip ? $ip : Vine_Request::getIp());
    }

    /**
     * Prepare IP address stored in database for visual output.
     * ---
     * @param   string  The stored IP address.
     * @return  string
     */
    public static function unstoreIp($ip)
    {
        return inet_ntop($ip);
    }
}
