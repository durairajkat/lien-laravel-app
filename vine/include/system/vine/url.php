<?php

/**
 * URL & Link Helper
 * ---
 * @author     Tell Konkle <tellkonkle@gmail.com>
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Url
{
    /**
     * Ensure URL starts with https:// or http://. Does not verify URL format.
     * ---
     * @param   string  The URL to format.
     * @param   string  [optional] The default protocol when not provided.
     * @param   string  [optional] Force the default protocol.
     * @return  string  The formatted URL
     */
    public static function format($url, $default = 'http://', $force = FALSE)
    {
        // URL is not a valid string, stop here
        if ( ! strlen($url)) {
            return $url;
        }

        // Force default protocol
        if ($force) {
            // Extract parts of URL
            $parts = parse_url($url);

            // Recompile URL with default protocol, stop here
            return $default
                 . (isset($parts['host'])     ? $parts['host']           : '')
                 . (isset($parts['path'])     ? $parts['path']           : '')
                 . (isset($parts['query'])    ? '?' . $parts['query']    : '')
                 . (isset($parts['fragment']) ? '#' . $parts['fragment'] : '');
        }

        // This URL is likely already formatted properly, so don't mess with it
        if (0 === strpos($url, 'http://') || 0 === strpos($url, 'https://')) {
            return $url;
        // URL just needs protocol added to beginning of it
        } else {
            return $default . $url;
        }
    }

    /**
     * Get the domain name or host from URL string. Does not verify URL format.
     * ---
     * @param   string
     * @return  string
     */
    public static function getDomain($input)
    {
        return parse_url($input, PHP_URL_HOST);
    }

    /**
     * See if URL is using the HTTPS protocol.
     * ---
     * @param   string
     * @return  bool
     */
    public static function isSecure($input)
    {
        return 0 === strpos($input, 'https://');
    }

    /**
     * See if specified URL is a YouTube video link.
     * ---
     * @param   string
     * @return  bool
     */
    public static function isYouTube($input)
    {
        // Standardize
        $input = self::format($input);

        // Invalid URL, stop here
        if ( ! Vine_Verify::url($input)) {
            return FALSE;
        }

        // Get the domain/host without "www." in it, get video ID
        $host = trim(str_replace('www.', '', strtolower(self::getDomain($input))));
        $id   = parse_str(parse_url($input, PHP_URL_QUERY), $pieces);

        // Invalid host, stop here
        if ( ! in_array($host, array('youtu.be', 'youtube.com'))) {
            return FALSE;
        }

        // Video ID not found, stop here
        if ( ! isset($pieces['v'])) {
            return FALSE;
        }

        // (bool)
        return preg_match('/^[a-zA-z0-9_-]{11}$/', $pieces['v']) > 0;
    }

    /**
     * See if specified URL is a Vimeo video link.
     * ---
     * @param   string
     * @return  bool
     */
    public static function isVimeo($input)
    {
        // Standardize
        $input = self::format($input);

        // Invalid URL, stop here
        if ( ! Vine_Verify::url(self::format($input))) {
            return FALSE;
        }

        // Get the domain/host without "www." in it
        $host = trim(str_replace('www.', '', strtolower(self::getDomain($input))));

        // Not a valid Vimeo URL
        if ( ! in_array($host, array('vimeo.com'))) {
            return FALSE;
        }

        // Everything looks good
        return TRUE;
    }

    /**
     * See if URL is valid.
     * ---
     * @param   string
     * @return  bool
     */
    public function isUrl($input)
    {
        return Vine_Verify::url($input);
    }
}
