<?php

/**
 * JSON (JavaScript Object Notation)
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 * @see        http://www.json.org
 */
class Vine_Json
{
    /**
     * Generate JSON headers for JSON results. Do NOT call this method until you're ready
     * to output a JSON result!
     * ---
     * @return  void.
     */
    public static function putHeaders()
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
    }

    /**
     * Output a JSONP result.
     * ---
     * @param   mixed   Data to return in result.
     * @param   string  (opt) $_GET parameter name for callback function.
     * @return  void
     */
    public static function jsonp($data, $callback = 'callback')
    {
        // Compile callback function name
        $result = isset($_GET[$callback])
                ? htmlspecialchars($_GET[$callback])
                : 'callback';

        // Compile callback function
        $result .= '(';
        $result .= self::encode($data);
        $result .= ');';

        // Output JSONP result
        self::putHeaders();
        echo $result;
    }

    /**
     * JSON encoding function.
     * ---
     * @param   mixed   The data to be encoded. Must be UTF-8 encoded data.
     * @param   int     JSON encoding options.
     * @return  string  A JSON encoded string.
     */
    public static function encode($data, $options = 0)
    {
        // PHP >= 5.5 requires all strings to be UTF-8 encoded for json_encode
        if (defined('JSON_UNESCAPED_UNICODE')) {
            return json_encode(Vine_Unicode::utf8($data), $options);
        // PHP < 5.5 does not require UTF-8 encoded strings for json_encode
        } else {
            return json_encode($data, $options);
        }
    }

    /**
     * JSON decoding function.
     * ---
     * @param   string  A JSON encoded string.
     * @param   bool    TRUE returns associative array. FALSE returns object.
     * @return  mixed   Associative 'array' or 'object' on success, NULL on failure.
     */
    public static function decode($json, $assoc = TRUE)
    {
        return json_decode($json, $assoc);
    }
}
