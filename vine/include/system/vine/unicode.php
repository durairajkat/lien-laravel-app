<?php

/**
 * Unicode Charsets & Encoding
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle.
 * @see        http://webcollab.sourceforge.net/unicode.html
 * @see        http://www.w3.org/International/questions/qa-forms-utf-8.en.php
 */
class Vine_Unicode
{
    /**
     * Detect whether or not a string contains only 7-bit ASCII characters.
     * ---
     * @param   string
     * @return  bool
     */
    public static function isAscii($str)
    {
        return (bool) preg_match('/^[\x00-\x7F]+$/', (string) $str);
    }

    /**
     * Convert a string or array of strings to UTF-8.
     * ---
     * @param   mixed
     * @return  mixed
     */
    public static function utf8($input)
    {
        // Recursively convert all strings in array
        if (is_array($input) && ! empty($input)) {
            // Loop through array and sanitize each string
            foreach ($input as $key => $value) {
                $input[$key] = static::utf8($value);
            }

            // Return sanitized array
            return $input;
        // Encode string
        } elseif (is_string($input) && $input !== '') {
            // Multibyte functions aren't enabled, force string to UTF-8
            if ( ! extension_loaded('mbstring')) {
                return iconv('UTF-8', 'UTF-8//IGNORE', static::sanitize($input));
            }

            // Detect strings in this order
            $detect   = ['UTF-8', 'ISO-8859-1', 'WINDOWS-1252'];
            $encoding = mb_detect_encoding($input, $detect, TRUE);

            // Already UTF-8 encoded, safely stop here
            if ('UTF-8' === $encoding) {
                return $input;
            // PHP has built-in ISO-8859-1 --> UTF-8 conversion, safely stop here
            } elseif ('ISO-8859-1' === $encoding) {
                return utf8_encode($input);
            // 'mbstring' extension can convert WINDOWS-1252 to UTF-8
            } elseif ('WINDOWS-1252' === $encoding) {
                return mb_convert_encoding($input, 'UTF-8', 'WINDOWS-1252');
            }
        }

        // Input didn't need encoded (ints, bools, floats, etc.)
        return $input;
    }

    /**
     * Sanitize a string against UTF-8, or recursively sanitize an array of strings.
     * ---
     * @param   mixed
     * @return  mixed
     */
    public static function sanitize($input)
    {
        // Recusion
        if (is_array($input) && ! empty($input)) {
            // Loop through array and sanitize each string
            foreach ($input as $key => $value) {
                $input[$key] = self::sanitize($value);
            }

            // Return sanitized array
            return $input;
        // Sanitize encoding
        } elseif (is_string($input) && $input !== '') {
            // Microsoft Word crap
            $msOne = array
            (
                "\xC2\xAB"     => '"', // « (U+00AB) in UTF-8
                "\xC2\xBB"     => '"', // » (U+00BB) in UTF-8
                "\xE2\x80\x98" => "'", // ‘ (U+2018) in UTF-8
                "\xE2\x80\x99" => "'", // ’ (U+2019) in UTF-8
                "\xE2\x80\x9A" => "'", // ‚ (U+201A) in UTF-8
                "\xE2\x80\x9B" => "'", // ‛ (U+201B) in UTF-8
                "\xE2\x80\x9C" => '"', // “ (U+201C) in UTF-8
                "\xE2\x80\x9D" => '"', // ” (U+201D) in UTF-8
                "\xE2\x80\x9E" => '"', // „ (U+201E) in UTF-8
                "\xE2\x80\x9F" => '"', // ‟ (U+201F) in UTF-8
                "\xE2\x80\xB9" => "'", // ‹ (U+2039) in UTF-8
                "\xE2\x80\xBA" => "'", // › (U+203A) in UTF-8
            );

            // More Microsoft Word crap
            $msTwo = array
            (
                chr(145) => "'",
                chr(146) => "'",
                chr(147) => '"',
                chr(148) => '"',
                chr(151) => '-',
            );

            // Standardize Microsoft Word crap
            $input = strtr($input, $msOne);
            $input = strtr($input, $msTwo);

            // Regex to strip overly long 3 byte sequences and UTF-16 surrogates
            $regex = '/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'
                   . '|[\x00-\x7F][\x80-\xBF]+'
                   . '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'
                   . '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'
                   . '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|'
                   . '(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S';

            // Replace invalid character sequences with a ?
            return preg_replace($regex, '?', $input);
        }

        // Input didn't need sanitized (ints, bools, floats, etc.)
        return $input;
    }

    /**
     * Convert a unicode string to ASCII.
     * ---
     * @param   string  Unicode string.
     * @return  string  ASCII string.
     */
    public static function toAscii($str)
    {
        // Setting local is necessary for even partially accurate iconv() conversion
        setlocale(LC_ALL, 'en_US.UTF8');

        // Ensure string is unicode
        $str = self::sanitize($str);

        // Don't trust iconv() with basic stuff because it's very unreliable
        $str = self::unAccent($str);

        // Do final unicode to ASCII conversion
        return iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
    }

    /**
     * Basic UTF-8 to ASCII accent conversion. Assumes input string is valid UTF-8. This
     * function does NOT convert a string to ASCII. It simply converts common unicode
     * ACCENTS to their nearest ASCII relative.
     * ---
     * @param   string  Accented UTF-8 string
     * @return  string  Unaccented UTF-8 string
     */
    public static function unAccent($str)
    {
        // Get unicode accents
        $translations = require VINE_PATH . 'accents.php';

        // Translate accents to nearest ASCII relative
        return str_replace(array_keys($translations), array_values($translations), $str);
    }
}
