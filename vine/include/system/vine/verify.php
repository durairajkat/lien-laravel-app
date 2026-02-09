<?php

/**
 * Input Verification
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Verify
{
    /**
     * Enable macro functionality.
     */
    use Vine_Trait_Macro;

    /**
     * Automatically apply a set of boolean rule functions to a particular value, based on
     * a string of assigned rules. The value will ALWAYS be the first argument passed to
     * the function or method rule.
     * ---
     * ) $bool = Vine_Verify::doRules('Foo', 'ascii;length[5,32]');
     * ) // Calls  : Vine_Verify::ascii('Foo'); Vine_Verify::length('Foo', 5, 32);
     * ) // Result : FALSE
     * ---
     * @param   mixed   The value to apply rules to.
     * @param   string  Rules. Separated by semicolon. Brackets or parentheses as args.
     * @param   mixed   Class where custom rules reside in. String or object.
     * @return  bool    TRUE if all rules met, FALSE otherwise.
     */
    public static function doRules($value, $rules, $source = NULL)
    {
        try {
            // No rules specified, accept value as valid
            if (NULL === $rules || is_bool($rules) || '' === $rules) {
                return TRUE;
            // Rules aren't valid
            } elseif (is_array($rules) || is_object($rules)) {
                throw new InvalidArgumentException('Argument 2 does not accept type: '
                        . gettype($rules));
            }

            // Convert rules to array, start arguments list
            $rules = explode(';', trim($rules, ';'));

            // Loop through each rule
            foreach ($rules as $rule) {
                // Start arguments list, start with no callback
                $rule   = trim($rule);
                $args   = array($value);
                $caller = FALSE;

                // Field is optional and empty, stop here
                if ('optional' === $rule && ! self::required($value)) {
                    return TRUE;
                // Optional rule isn't a function, so just skip this item in loop
                } elseif ('optional' === $rule) {
                    continue;
                }

                // Get this rule's arguments using brackets as delimiter
                if (FALSE !== strpos($rule, '[')) {
                    $params = explode('[', $rule);
                    $rule   = $params[0];
                    $params = str_replace(']', '', $params[1]);
                    $params = explode(',', $params);
                    $args   = array_merge($args, $params);
                // Get this rule's arguments using parentheses as delimiter
                } elseif (FALSE !== strpos($rule, '(')) {
                    $params = explode('(', $rule);
                    $rule   = $params[0];
                    $params = str_replace(')', '', $params[1]);
                    $params = explode(',', $params);
                    $args   = array_merge($args, $params);
                }

                // Valid callback: custom class method
                if (is_callable([$source, $rule])) {
                    $caller = $source;
                // Valid callback: class method
                } elseif (is_callable(['Vine_Verify', $rule])) {
                    $caller = 'Vine_Verify';
                // Valid callback: native PHP or global function
                } elseif (is_callable($rule)) {
                    $caller = NULL;
                // Valid callback not found
                } else {
                    throw new BadMethodCallException('Rule "' . $rule . '" is not a '
                            . 'valid callback function.');
                }

                // Simple function call
                if (NULL === $caller) {
                    $result = call_user_func_array($rule, $args);
                // Class method call
                } else {
                    $result = call_user_func_array([$caller, $rule], $args);
                }

                // Return early (faster)
                if ( ! $result) {
                    return FALSE;
                }
            }

            // Everything was valid
            return TRUE;
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Verify that an input only consists of letters. This means letters A-Z. Any other
     * character will render the input invalid.
     * ---
     * @param   mixed
     * @return  input
     */
    public static function alpha($input)
    {
        return (bool) preg_match("/^[a-zA-Z]+$/", (string) $input);
    }

    /**
     * Verify that an input is alpha numeric. This means letters A-Z, and numbers 0-9.
     * Decimal points, commas, and other characters will render the input invalid.
     * ---
     * @param   mixed
     * @return  bool
     */
    public static function alphaNumeric($input)
    {
        return (bool) preg_match("/^[a-zA-Z0-9]+$/", (string) $input);
    }

    /**
     * Verify that an input contains only 7-bit ASCII characters.
     * ---
     * @param   string
     * @return  bool
     */
    public static function ascii($input)
    {
        if ( ! trim($input)) {
            return TRUE;
        }

        return (bool) preg_match('/^[\x00-\x7F]+$/', (string) $input);
    }

    /**
     * Verify a calendar date against a specified format. Will attempt to verify format
     * and date.
     * ---
     * ) Format Options (case insensitive):
     * )
     * ) Y or y : 4 digits
     * ) M or m : 2 digits
     * ) D or d : 2 digits
     * ---
     * ) Examples:
     * )
     * ) Y-m-d : YYYY-MM-DD
     * ) y-m-d : YYYY-MM-DD
     * ) y/d/d : YYYY/MM/DD
     * ) m/d/Y : MM/DD/YYYY
     * ) m/d   : MM/DD
     * ) m/y   : MM/YYYY
     * ) my    : MMYYYY
     * ---
     * ) // FALSE (valid format, invalid date)
     * ) $result = Vine_Verify::calendar('2012-13-24', 'y-m-d');
     * )
     * ) // TRUE (valid format, valid date)
     * ) $result = Vine_Verify::calendar('2012-12-24', 'y-m-d');
     * )
     * ) // FALSE (invalid format, valid date)
     * ) $result = Vine_Verify::calendar('20121224', 'y-m-d');
     * )
     * ) // TRUE (valid format, valid date)
     * ) $result = Vine_Verify::calendar('09-11-2001', 'm-d-y');
     * ---
     * @param   mixed   Date string to validate.
     * @param   string  Yy, Mm, Dd combinations.
     * @return  bool
     */
    public static function calendar($input, $format = 'Y-m-d')
    {
        // Certainly not a date
        if ( ! is_string($input) && ! is_int($input) && ! is_float($input)) {
            return FALSE;
        }

        // Date patterns; regex patterns; generate regex; escape regex
        $search  = array('/[yY]/', '/[mM]/', '/[dD]/');
        $replace = array('(?P<Y>[0-9]{4})', '(?P<M>[0-9]{1,2})', '(?P<D>[0-9]{1,2})');
        $pattern = preg_replace($search, $replace, $format);
        $pattern = str_replace('/', '\/', $pattern);

        // Invalid format, stop here
        if ( ! preg_match('/' . $pattern . '/', $input, $matches)) {
            return FALSE;
        }

        // Use placeholder year if it wasn't a requirement
        if ( ! isset($matches['Y'])) {
            $matches['Y'] = '2001';
        }

        // Use placeholder month if it wasn't a requirement
        if ( ! isset($matches['M'])) {
            $matches['M'] = '1';
        }

        // Use placeholder month if it wasn't a requirement
        if ( ! isset($matches['D'])) {
            $matches['D'] = '1';
        }

        // (bool) See if this is a valid date
        return checkdate($matches['M'], $matches['D'], $matches['Y']);
    }

    /**
     * Verify a hex color. Valid hex format is considered #ffffff or #FFFFFF. Does not
     * accept shorthand colors (i.e. #fff) as valid, as this may lead to confusion.
     * ---
     * @param   string
     * @return  bool
     */
    public static function color($input)
    {
        return (bool) preg_match('/^#[a-fA-F0-9]{6}$/i', (string) $input);
    }

    /**
     * Verify a two-letter country code against the ISO 3166-1 country code standard.
     * ---
     * @param   string
     * @return  bool
     * @see     http://en.wikipedia.org/wiki/ISO_3166-1
     */
    public static function country($input)
    {
        // Certainly not a country (faster)
        if ( ! is_string($input)) {
            return FALSE;
        }

        // Get countries array
        $countries = require VINE_PATH . 'countries.php';
        return isset($countries[$input]);
    }

    /**
     * Verify a credit card number using the Luhn / Mod 10 algorithm.
     * ---
     * @param   string
     * @return  bool
     * @see     http://en.wikipedia.org/wiki/Luhn_algorithm
     */
    public static function creditCard($input) {
        // Replace common input characters people use when they enter a card number
        $input = str_replace(array(' ', '-', '.'), '', (string) $input);

        // Card number must be a digit
        if ( ! ctype_digit($input)) {
            return FALSE;
        }

        // Checksum that will be generated, boolean swap
        $checksum = 0;
        $alt      = FALSE;

        // Generate a checksum (iterate through card number from right to left)
        for ($i = strlen($input) - 1; $i >= 0; $i--) {
            // Every second digit from the right
            if ($alt) {
                // Double this digit
                $double = $input[$i] * 2;

                // Subtract 9 from double when double is 10 or greater
                $input[$i] = ($double > 9) ? $double = $double - 9 : $double;
            }

            // Generated checksum every digit in loop (every second digit gets doubled)
            $checksum += $input[$i];

            // Swap boolean
            $alt = ! $alt;
        }

        // Card number considered valid if checksum is a multiple of 10 and is not 0
        return $checksum % 10 == 0 && $checksum != 0;
    }

    /**
     * Check to see if an input is a valid digit, or set of digits.
     * ---
     * @param   mixed
     * @return  bool
     */
    public static function digit($input)
    {
        return is_numeric($input) && preg_match('/^-?[0-9]+$/', $input);
    }

    /**
     * Verify a string to see if it's a valid domain name format. Does not check to see if
     * the domain name actually exists.
     * ---
     * @param   mixed
     * @return  bool
     * @author  (regex)   Shaun Inman <http://www.shauninman.com>
     * @author  (regex)   Tell Konkle <tellkonkle@gmail.com>
     * @author  (method)  Tell Konkle <tellkonkle@gmail.com>
     * @see     http://shauninman.com/archive/2006/05/08/validating_domain_names
     */
    public static function domain($input)
    {
        // Certainly not a domain name (faster)
        if ( ! is_string($input)) {
            return FALSE;
        }

        // Regex to verify a domain name is valid
        $regex = '/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)'
               . '+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)'
               . '|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)'
               . '|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]'
               . '|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]'
               . '|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)'
               . '|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)'
               . '|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]'
               . '|travel)|u[agkmsyz]|v[aceginu]|w[fs]|xxx|y[etu]|z[amw])$/i';

        // Run regex, return as bool
        return (bool) preg_match($regex, $input);
    }

    /**
     * Verify a string to see if it's a valid email address format.
     * ---
     * @param   string
     * @return  bool
     */
    public static function email($input)
    {
        // Certainly not an email address (faster)
        if ( ! is_string($input)) {
            return FALSE;
        }

        // @see  https://stackoverflow.com/a/5855853/1148902
        return filter_var($input, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $input);
    }

    /**
     * Verify that a value is inside a specified set a value possibilities.
     * ---
     * [!!!] Does NOT type check.
     * ---
     * @param   mixed  Value to check.
     * @params  mixed  Multiple arguments. All possible values.
     * @return  bool
     */
    public static function inside($value)
    {
        // Get arguments
        $arg = func_get_args();
        $num = func_num_args();

        // No possible values, assume TRUE
        if (1 === $num) {
            return TRUE;
        }

        // Loop through all value possibilities until a match is found
        for ($i = 1; $i < $num; $i++) {
            // An array of possible values
            if (is_array($arg[$i]) && ! empty($arg[$i])) {
                // Loop through array of possible values
                foreach ($arg[$i] as $possible) {
                    // Value found (don't type check)
                    if ($value == $possible) {
                        return TRUE;
                    }
                }
            // Value found (don't type check)
            } elseif ($value == $arg[$i]) {
                return TRUE;
            }
        }

        // Match not found
        return FALSE;
    }

    /**
     * Verify an IP address.
     * ---
     * @param   mixed  IP address to verify.
     * @param   bool   Consider private IP's, like 192.168.0.0, to be valid?
     * @return  bool
     */
    public static function ip($input, $private = TRUE)
    {
        // Setting, filter, and filter flags
        $private = filter_var($input, FILTER_VALIDATE_BOOLEAN);
        $filter  = FILTER_VALIDATE_IP;
        $flags   = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_RES_RANGE;

        // Don't consider private IP's valid
        if (FALSE === $private) {
            return (bool) filter_var($input, $filter, $flags | FILTER_FLAG_NO_PRIV_RANGE);
        // Consider private IP's valid
        } else {
            return (bool) filter_var($input, $filter, $flags);
        }
    }

    /**
     * Verify the character length of a string.
     * ---
     * @param   scalar
     * @param   int
     * @param   int
     * @return  bool
     */
    public static function length($input, $min, $max = 0)
    {
        try {
            // Verify rules
            if ( ! is_numeric($min) || ! is_numeric($max)) {
                throw new InvalidArgumentException('Invalid validation rule. Min and max '
                        . 'must be numeric.');
            }

            // The length of the string
            $length = strlen(trim((string) $input));

            // Only verify against minimum length
            if ($max == 0) {
                return $length >= $min;
            // Verify against minimum and maximum length
            } else {
                return $length >= $min && $length <= $max;
            }
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Verify that a money value is entered correctly.
     * ---
     * @param   mixed
     * @param   bool
     * @return  bool
     */
    public static function money($input, $negative = FALSE)
    {
        // Do not allow negative values
        if (FALSE === filter_var($negative, FILTER_VALIDATE_BOOLEAN)) {
            return preg_match("/^[0-9.\-]+$/", (string) $input) && $input >= 0;
        // Allow negative values
        } else {
            return preg_match("/^[0-9.\-]+$/", (string) $input);
        }
    }

    /**
     * Verify that a value is numeric. This function is quite different from the native
     * PHP is_numeric() function, in that exponential parts and hexadecimal notations are
     * not considered valid, like they are in the native is_numeric() function.
     * ---
     * @param   string|float|int
     * @return  bool
     */
    public static function numeric($input)
    {
        return (bool) preg_match("/^[0-9.\-]+$/", (string) $input);
    }

    /**
     * Verify that a value is NOT inside a specified set a value possibilities.
     * ---
     * [!!!] Does NOT type check.
     * ---
     * @param   mixed  Value to check.
     * @params  mixed  Multiple arguments. All values not wanted.
     * @return  bool
     */
    public static function outside($value)
    {
        // Get arguments
        $arg = func_get_args();
        $num = func_num_args();

        // No possible values, assume TRUE
        if (1 === $num) {
            return TRUE;
        }

        // Loop through all value possibilities until a match is found
        for ($i = 1; $i < $num; $i++) {
            // An array of possible values
            if (is_array($arg[$i]) && ! empty($arg[$i])) {
                // Loop through array of possible values
                foreach ($arg[$i] as $possible) {
                    // Value found (don't type check)
                    if ($value == $possible) {
                        return FALSE;
                    }
                }
            // Value found (don't type check)
            } elseif ($value == $arg[$i]) {
                return FALSE;
            }
        }

        // Match not found
        return TRUE;
    }

    /**
     * Verify a phone number against a simple regex. Ideal for applications using US
     * numbers as well as international phone numbers. US-only applications may require
     * more advanced phone number validation.
     * ---
     * @param   mixed
     * @return  bool
     */
    public static function phone($input)
    {
        // Normalize input
        $input = strtolower((string) $input);

        // Strip unwanted characters before verifying
        $input = str_replace([' ', '-', '/', '(', ')', ',', '.', '+'], '', $input);

        // Remove common "extension" descriptions and replace with "x"
        $input = preg_replace('/(ext(ension)?)|(ex)|(e)/i', 'x', $input);

        // Validate against modified EPP (+CCC.NNNNNNNNNNxEEEE => CCCNNNNNNNNNNxEEEE)
        return (bool) preg_match('/^[0-9]{6,17}(x[0-9]{1,4})?$/', $input);
    }

    /**
     * Verify that a number is positive.
     * ---
     * @param   mixed
     * @param   bool   Consider zero (0) a positive number? Defaults to TRUE.
     * @return  bool
     */
    public static function positive($input, $allowZero = TRUE)
    {
        return Vine_Quick::toBool($allowZero)
            ? is_numeric($input) && $input >= 0
            : is_numeric($input) && $input > 0;
    }

    /**
     * Rapidly verify that a required input has been provided, regardless of desired
     * value. Any FALSE, NULL, or empty string/array/object is considered invalid.
     * ---
     * @param   mixed
     * @return  bool
     */
    public static function required($input)
    {
        // Convert objects to arrays (to later see if empty)
        if (is_object($input)) {
            $input = (array) $input;
        }

        // All values that are considered empty
        if (   FALSE   === $input
            || NULL    === $input
            || [] === $input
            || ''      === trim($input)
        ) {
            return FALSE;
        // A valid input value
        } else {
            return TRUE;
        }
    }

    /**
     * Verify that a number falls within a specified min/max range.
     * ---
     * @param   int|float
     * @param   int|float
     * @param   int|float
     * @return  bool
     */
    public static function range($input, $min, $max)
    {
        try {
            // Verify rules
            if ( ! is_numeric($min) || ! is_numeric($max)) {
                throw new InvalidArgumentException('Invalid validation rule. Min and max '
                        . 'must be numeric.');
            }

            // (bool)
            return $input >= $min && $input <= $max;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Verify a form/URL security token for an action request is valid.
     * ---
     * @param   string
     * @return  bool
     */
    public static function token($input)
    {
        return Vine_Security::checkToken($input);
    }

    /**
     * Verify a string to see if it's a valid URL format. Does not check to see if the URL
     * actually exists.
     * ---
     * @param   string
     * @return  bool
     */
    public static function url($input)
    {
        // Certainly not a URL (faster)
        if ( ! is_string($input) || FALSE === strpos($input, '.')) {
            return FALSE;
        }

        // (bool)
        return filter_var($input, FILTER_VALIDATE_URL);
    }

    /**
     * Verify a string to see if it's a valid YouTube or Vimeo URL.
     * ---
     * @param   string
     * @return  bool
     */
    public static function video($input)
    {
        return Vine_Url::isYouTube($input) || Vine_Url::isVimeo($input);
    }

    /**
     * Validate a Vehicle Identification Number (VIN).
     * ---
     * @param   string  VIN to validate.
     * @return  bool
     * @author  original  Jordan Stephens
     * @author  modified  Tell Konkle
     * @see     http://stackoverflow.com/questions/3831764/php-vin-number-validation-code
     */
    public static function vin($input)
    {
        // Standardize VIN
        $vin = strtolower($input);

        // Simple regex check (stop here if it fails)
        if ( ! preg_match('/^[^\Wioq]{17}$/', $vin)) {
            return FALSE;
        }

        // 17 weights total (for 17 character VIN)
        $weights = array(8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2);

        // Numberic value of each valid VIN character (i, o, q are invalid)
        $transliterations = array(
            'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4,
            'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8,
            'j' => 1, 'k' => 2, 'l' => 3, 'm' => 4,
            'n' => 5, 'p' => 7, 'r' => 9, 's' => 2,
            't' => 3, 'u' => 4, 'v' => 5, 'w' => 6,
            'x' => 7, 'y' => 8, 'z' => 9
        );

        // Sum = transliterations * weight of each character in VIN
        $sum = 0;

        // Loop through characters of VIN
        for ($i = 0 ; $i < strlen($vin) ; $i++) {
            if ( ! is_numeric($vin{$i})) {
                $sum += $transliterations[$vin{$i}] * $weights[$i];
            } else {
                $sum += $vin{$i} * $weights[$i];
            }
        }

        // Find checkdigit by taking the mod of the sum
        $checkdigit = $sum % 11;

        // Checkdigit of 10 is represented by "X"
        if (10 == $checkdigit) {
            $checkdigit = 'x';
        }

        // (bool)
        return $checkdigit == $vin{8};
    }

    /**
     * Verify a string to ensure that it contains only letters (A-Z), numbers (0-9),
     * spaces, dashes, and underscores. No other characters are allowed.
     * ---
     * @param   string
     * @return  bool
     */
    public static function words($input)
    {
        return (bool) preg_match("/^[a-zA-Z0-9 _\-]+$/", (string) $input);
    }
}
