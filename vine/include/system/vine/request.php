<?php

/**
 * Request Data (GET, POST, COOKIE)
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Request
{
    /**
     * Request method. POST or GET.
     * ---
     * @var  string
     */
    protected $method = NULL;

    /**
     * Sanitized superglobals.
     * ---
     * @var  array
     */
    private $cookie = [];
    private $get    = [];
    private $post   = [];

    /**
     * When TRUE, forces isAjax() method to return TRUE.
     * ---
     * @var  bool
     */
    private static $forcedAjax = FALSE;

    /**
     * Prepare and sanitize $_COOKIE, $_GET, and $_POST superglobals.
     * ---
     * @param   bool  Sanitize all input with Vine_Unicode?
     * @return  void
     */
    public function __construct($unicode = TRUE)
    {
        $this->cookie = $this->sanitize($_COOKIE, $unicode);
        $this->get    = $this->sanitize($_GET, $unicode);
        $this->post   = $this->sanitize($_POST, $unicode);
        $this->method  = self::getMethod();
    }

    /**
     * Get the request method was used to access the page.
     * ---
     * @return  string  POST or GET.
     */
    public static function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && 'POST' === $_SERVER['REQUEST_METHOD']) {
            return 'POST';
        } else {
            return 'GET';
        }
    }

    /**
     * @see  self::getReferer()
     */
    public static function getReferrer(...$args)
    {
        return self::getReferer(...$args);
    }

    /**
     * Get the referer of the current request if it's available.
     * ---
     * @param   bool    [optional] Make referer relative if from same host? Default = FALSE.
     * @return  string  NULL if referer is not available, string otherwise.
     */
    public static function getReferer($relative = FALSE)
    {
        if ( ! isset($_SERVER['HTTP_REFERER'])) {
            return NULL;
        } else {
            return str_replace(self::getHost(TRUE), '', (string) $_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * Get the IP address used to make the current request.
     * ---
     * @return  string
     */
    public static function getIp()
    {
        if ( ! isset($_SERVER['REMOTE_ADDR'])) {
            return '127.0.0.1';
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Get the HTTP host. If host cannot be found the server name will be returned.
     * ---
     * @param   bool    When TRUE, http:// or https:// is prepended to host name.
     * @return  string  Host name.
     */
    public static function getHost($complete = FALSE)
    {
        $protocol = self::isSecure() ? 'https://' : 'http://';
        $prepend  = $complete ? $protocol : '';

        if ( ! isset($_SERVER['HTTP_HOST']) && isset($_SERVER['SERVER_NAME'])) {
            return $prepend . $_SERVER['SERVER_NAME'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            return $prepend . $_SERVER['HTTP_HOST'];
        } else {
            return NULL;
        }
    }

    /**
     * Get the mobile OS of the current user. If user is not using a mobile device, bool
     * FALSE is returned. Possible other returned values are 'ios', 'blackberry', and
     * 'android.'
     * ---
     * @param   bool         Distingush between 'ipad' and 'iphone'? 'ios' if FALSE.
     * @return  string|bool  FALSE if not a mobile device, string otherwise.
     */
    public static function getMobileOs($strict = FALSE)
    {
        // Get user agent if available
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : FALSE;

        // No user agent available, assume it's NOT a mobile OS
        if ( ! $agent) {
            return FALSE;
        // User is browsing with an iPad
        } elseif (stristr($agent, 'ipad')) {
			return $strict ? 'ipad' : 'ios';
        // User is browsing with an iPhone
		} elseif (stristr($agent, 'iphone') || strstr($agent, 'iphone')) {
			return $strict ? 'iphone' : 'ios';
        // User is browsing with a blackberry device
		} elseif (stristr($agent, 'blackberry')) {
			return 'blackberry';
        // User is browsing with an android device
		} elseif (stristr($agent, 'android')) {
			return 'android';
        // User is browsing with a PC or other device
		} else {
            return FALSE;
        }
    }

    /**
     * Parse locales browser has sent and return first locale that appears in the specified
     * array.
     * ---
     * @param   array        An array of locales. All locales must be 2 characters in length.
     * @return  bool|string  FALSE if locale not found or supported, string otherwise.
     */
    public static function getLocale(array $supported)
    {
        // Browser not sending locale info, stop here
        if (   ! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
            || ! strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])
        ) {
            return FALSE;
        }

        // Break languages into pieces (languages and q-factors)
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
            $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            $locales
        );

        // Browser hasn't requested any locales, stop here
        if ( ! isset($locales) || ! count($locales[1])) {
            return FALSE;
        }

        // Create a list like 'en' => 0.8
        $langs = array_combine($locales[1], $locales[4]);

        // set default to 1 for any without q factor
        foreach ($langs as $lang => $q) {
            // Only parse first two characters of locale
            $lang = strtolower(substr($lang, 0, 2));

            // No q-factor provided, auto-set
            if ($q === '') {
                $langs[$lang] = 1;
            }
        }

        // Sort list based on q-factor
        arsort($langs, SORT_NUMERIC);

        // Loop through each locale found until one matches locale list
        foreach ($langs as $lang => $q) {
            // Language found, stop here
            if (in_array($lang, $supported)) {
                return $lang;
            }
        }

        // No locale found
        return FALSE;
    }

    /**
     * Retrieve cookie data.
     * ---
     * @param   string  [optional] When NULL, the entire COOKIE array is returned.
     * @return  mixed
     */
    public function cookie($name = NULL)
    {
        // Return the entire COOKIE array
        if (NULL === $name) {
            return $this->cookie;
        }

        // Return requested field
        return Vine_Array::getKey($this->cookie, $name);
    }

    /**
     * Retrieve GET data.
     * ---
     * @param   string  [optional] When NULL, the entire GET array is returned.
     * @return  mixed
     */
    public function get($name = NULL)
    {
        // Return the entire GET array
        if (NULL === $name) {
            return $this->get;
        }

        // Return requested field
        return Vine_Array::getKey($this->get, $name);
    }

    /**
     * Retrieve input data. If request method was POST, the applicable POST field is
     * returned, otherwise the applicable GET field is returned.
     * ---
     * @param   string  [optional] When NULL, the entire GET or POST array is returned.
     * @return  mixed
     */
    public function input($name = NULL)
    {
        // This is a POST request
        if ('POST' === $this->method) {
            // Return the entire POST array
            if (NULL === $name) {
                return $this->post;
            }

            // Return the requested field
            return Vine_Array::getKey($this->post, $name);
        // This is a GET request
        } else {
            // Return the entire GET array
            if (NULL === $name) {
                return $this->get;
            }

            // Return the requested field
            return Vine_Array::getKey($this->get, $name);
        }
    }

    /**
     * Retrieve POST data.
     * ---
     * @param   string  [optional] When NULL, the entire POST array is returned.
     * @return  mixed
     */
    public function post($name = NULL)
    {
        // Return the entire POST array
        if (NULL === $name) {
            return $this->post;
        }

        // Return the requested field
        return Vine_Array::getKey($this->post, $name);
    }

    /**
     * Add POST or GET data.
     * ---
     * @param   string  The field name.
     * @param   mixed   The field value.
     * @return  void
     */
    public function add($name, $value)
    {
        // Add to POST request
        if ('POST' === $this->method) {
            $this->post[$name] = $value;
        // Add to GET request
        } else {
            $this->get[$name] = $value;
        }
    }

    /**
     * Remove input data. If request method was POST, the applicable POST field is
     * removed, otherwise the applicable GET field is removed.
     * ---
     * @param   string  [optional] When NULL, all input data is removed.
     * @return  void
     */
    public function remove($name)
    {
        // This is a POST request
        if ('POST' === $this->method) {
            // Clear the entire POST array, finished
            if (NULL === $name) {
                $this->post = [];
                return;
            }

            // Clear specific field
            Vine_Array::unsetKey($this->post, $name);
        // This is a GET request
        } else {
            // Clear the entire GET array, finished
            if (NULL === $name) {
                $this->get = [];
                return;
            }

            // Clear specific field
            Vine_Array::unsetKey($this->get, $name);
        }
    }

    /**
     * Force isAjax() method to return TRUE.
     * ---
     * @param   bool  [optional] Force isAjax to return TRUE? Defaults to TRUE.
     * @return  void
     */
    public static function forceAjax($force = TRUE)
    {
        self::$forcedAjax = (bool) $force;
    }

    /**
     * Automatically detect whether or not script is being executed from command line.
     * ---
     * @return  bool  TRUE if execution is from CLI, FALSE otherwise.
     */
    public static function isCli()
    {
        return 0 === strcasecmp('cli', php_sapi_name());
    }

    /**
     * Automatically detect whether or not a page request is an AJAX call.
     * ---
     * @return  bool  TRUE if AJAX call, FALSE otherwise.
     */
    public static function isAjax()
    {
        // Most JavaScript frameworks and libraries will send this HTTP value
        $auto = strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest';

        // Sometimes $_GET or $_POST will contain an ajax parameter
        $aGet  = isset($_GET['ajax'])  && $_GET['ajax'];
        $aPost = isset($_POST['ajax']) && $_POST['ajax'];

        // Sometimes $_GET or $_POST will contain a JSON parameter
        $jGet  = isset($_GET['json'])  && $_GET['json'];
        $jPost = isset($_POST['json']) && $_POST['json'];

        // (bool) If auto-detect or forced, it's an AJAX request
        return $auto || self::$forcedAjax || $aGet || $aPost || $jGet || $jPost;
    }

    /**
     * See if current request was made using HTTPS.
     * ---
     * @return  bool  TRUE if request made using HTTPS, FALSE otherwise.
     */
    public static function isSecure()
    {
        if (   (isset($_SERVER['HTTPS'])
            && ! empty($_SERVER['HTTPS'])
            && 'off' !== $_SERVER['HTTPS'])
            || 443 == $_SERVER['SERVER_PORT']
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Recursively sanitize REQUEST data. Does not damage types.
     * ---
     * ) Trim all request data.
     * ) Stripslashes on all request data if magic quotes is on.
     * ) Standardize new lines
     * ) Sanitize charset with Vine_Unicode
     * ---
     * @param   mixed  Data to sanitize.
     * @param   bool   [optional] Sanitize with Vine_Unicode? Default = TRUE.
     * @return  mixed  Sanitized data.
     */
    private function sanitize($input, $unicode = TRUE)
    {
        // Recursion
        if (is_array($input) && ! empty($input)) {
            // Loop through array and sanitize each string
            foreach ($input as $key => $value) {
                $input[$key] = $this->sanitize($value);
            }

            // Return sanitized array
            return $input;
        // Sanitize input
        } elseif (is_string($input)) {
            // See if PHP's greatest curse is enabled
            $magic = function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc();

            // Standardize newlines
            $input = str_replace(array("\r\n", "\r"), "\n", $input);

            // Sanitize charset with Vine_Unicode
            if (TRUE === $unicode) {
                $input = Vine_Unicode::sanitize($input);
            }

            // Trim and stripslashes (if applicable)
            return $magic ? trim(stripslashes($input)) : trim($input);
        }

        // Sanitized result
        return $input;
    }
}
