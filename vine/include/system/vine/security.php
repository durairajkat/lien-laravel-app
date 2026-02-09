<?php

/**
 * Security
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Security
{
    /**
     * Cipher algorithm to use for encryption/decryption.
     */
    const CIPHER = 'AES-256-CBC-HMAC-SHA256';

    /**
     * Flag that's appended on the end of an encrypted string followed by a key. When the flag
     * and key are present, it means the string has been encrypted by Vine. It's primarily
     * used to automatically decrypt database fields.
     */
    const FLAG = '::V::';

    /**
     * Hash algorithm to use for generating HMAC keys to store alongside encrypted data.
     */
    const HASH = 'SHA256';

    /**
     * Output common security headers.
     * ---
     * @return  void
     */
    public static function headers()
    {
        // Don't output PHP version
        header_remove('X-Powered-By');

        // Ditch IE compatibility mode
        header('X-UA-Compatible: IE=edge,chrome=1');

        // Ensure XSS protection is enabled
        header('X-XSS-Protection: 1');

        // Don't allow content to be embedded in iframes until it's same origin
        header('X-Frame-Options: sameorigin');

        // Only parse referrer headers if they came from the same security protocol
        header('Referrer-Policy: no-referrer-when-downgrade');

        // Only enable strong headers when the entire app should be served via HTTPS
        if (Vine_Registry::getSetting(Vine::GLOBAL_NOSNIFF)) {
            header('X-Content-Type-Options: nosniff');
        }

        // Instruct browser to use HTTPS
        if (Vine_Registry::getSetting(Vine::GLOBAL_HTTPS)) {
            header('Strict-Transport-Security: max-age=63072000; includeSubDomains');
        }
    }

    /**
     * Make a random string of a specified length.
     * ---
     * ) This method does not generate a cryptographically secure string.
     * ---
     * @param   int     Length random string should be.
     * @return  string  Random string.
     */
    public static function makeRandomString($length = 32)
    {
        // Character set to pull random characters from
        $chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $result = '';

        // Iterate a specified number of times and get a random character on each iteration
        for ($i = 0; $i < (int) $length; $i++) {
            $result .= $chars[mt_rand(0, 61)];
        }

        // Random string with specified number of characters
        return $result;
    }

    /**
     * Make a random password of a specified length. All generated passwords are all
     * lowercase. It will not generate passwords containing hard to distringish characters
     * like: 1, l, o, 0, O.
     * ---
     * ) This method does not generate a cryptographically secure password. This method is
     * ) intended for generating temporary user passwords and verification codes that can be
     * ) read and typed without the need to copy and paste. It should not be used for any
     * ) other purpose.
     * ---
     * @param   int     Length generated password should be.
     * @return  string  Generated password.
     */
    public static function makePassword($length = 8)
    {
        // Character set to pull random characters from
        $chars  = '23456789abcdefghjkmnpqrstuvwxyz';
        $result = '';

        // Iterate a specified number of times and get a random character on each iteration
        for ($i = 0; $i < (int) $length; $i++) {
            $result .= $chars[mt_rand(0, 30)];
        }

        // Random string with specified number of characters
        return $result;
    }

    /**
     * Generate a valid Microsoft GUID (v4) implementation of the RFC 4122 UUID standard.
     * ---
     * @return  string  A valid v4 GUID.
     * @see     http://en.wikipedia.org/wiki/Globally_unique_identifier
     * @see     http://tools.ietf.org/html/rfc4122
     */
    public static function makeGuid()
    {
        // Use built-in COM function (Windows only)
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }

        // Seed the random number generator
        mt_srand((double) microtime() * 10000);

        // Generate characters for GUID
        $chars = strtoupper(md5(uniqid(rand(), TRUE)));

        // Compile GUID
        return  substr($chars, 0, 8)  . '-'
              . substr($chars, 8, 4)  . '-'
              . substr($chars, 12, 4) . '-'
              . substr($chars, 16, 4) . '-'
              . substr($chars, 20, 12);
    }

    /**
     * Generate a valid RFC 4122 UUID (v4).
     * ---
     * @return  string  A valid v4 UUID.
     * @see     http://en.wikipedia.org/wiki/Universally_unique_identifier
     * @see     http://tools.ietf.org/html/rfc4122
     */
    public static function makeUuid() {
        return strtoupper(sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // time-low
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // time-mid
            mt_rand(0, 0xffff),

            // time-high-and-version
            mt_rand(0, 0x0fff) | 0x4000,

            // clock-seq-and-reserved, clock-seq-low
            mt_rand(0, 0x3fff) | 0x8000,

            // node
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        ));
    }

    /**
     * Make a signed anti-CSRF token and save it to session.
     * ---
     * @param   bool    Force a new token to be generated even if one already exists?
     * @return  string  Generated token.
     */
    public static function makeToken($force = FALSE)
    {
        // Use existing token
        if (FALSE === $force) {
            return Vine_Session::getToken();
        }

        // Generate new token, save to session, return new token
        $token = sha1(self::makeRandomString(32));
        Vine_Session::setToken($token);
        return $token;
    }

    /**
     * Check to see if an anti-CSRF token exists.
     * ---
     * @return  bool  TRUE if token is valid, FALSE otherwise.
     */
    public static function checkToken($input)
    {
        // Potential CSRF attack
        if ($input !== Vine_Session::getToken()) {
            $ref = Vine_Request::getReferer();
            self::makeToken(TRUE);
            Vine_Log::logEvent('Potential CSRF attack from ' . ($ref ? $ref : 'unknown'));
            return FALSE;
        }

        // Token is valid
        return TRUE;
    }

    /**
     * Mask/decode a string that can be decoded using JavaScript's encodeURI() function.
     * ---
     * @param   string  Data to mask.
     * @return  string  Masked string.
     */
    public static function mask($input, $hex = '')
    {
        // Loop through each character in string and convert it to hex
        for ($i = 0; $i < strlen($input); $i++) {
            $hex .= '%' . bin2hex($input[$i]);
        }

        // Masked string
        return $hex;
    }

    /**
     * Encrypt a string.
     * ---
     * @param   string  String to encrypt.
     * @param   bool    [optional] Flag data as "encrypted-by-vine"? Default = TRUE.
     * @return  string  Encrypted string.
     */
    public static function encrypt($data, $flag = TRUE)
    {
        // (mixed) Crypt configuration
        $config = Vine_Registry::getConfig(Vine::CONFIG_CRYPT);
        $flag   = $config['auto'] && $flag ? self::FLAG . $config['flag'] : '';
        $key    = $config['key'];

         // (bin) Create initialization vector (IV/nonce)
        $iv = random_bytes(openssl_cipher_iv_length(self::CIPHER));

        // (bin) Encrypt data
        $data = openssl_encrypt($data, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);

        // (bin) [data + key] hash
        $hmac = hash_hmac(self::HASH, $data, $key, TRUE);

        // (string) Base64 is longer but more compatible for RDBMS storage types (vs binary)
        return base64_encode($iv . $hmac . $data) . $flag;
    }

    /**
     * Decrypt a string.
     * ---
     * @param   string       String to decrypt.
     * @return  bool|string  FALSE if decryption failed, string otherwise.
     */
    public static function decrypt($data)
    {
        // (mixed) Crypt configuration
        $config = Vine_Registry::getConfig(Vine::CONFIG_CRYPT);
        $key    = $config['key'];
        $length = openssl_cipher_iv_length(self::CIPHER);

        // (string) Automatically remove flag from encrypted data
        if ($config['auto'] && self::isEncrypted($data)) {
            $data = self::removeFlag($data);
        }

        // (string) Split the encrypted string up into substrings: {$iv . $hmac . $crypt}
        $bin   = base64_decode($data);
        $iv    = substr($bin, 0, $length);
        $hmac  = substr($bin, $length, 32);
        $crypt = substr($bin, $length + 32);
        $value = openssl_decrypt($crypt, self::CIPHER, $key, OPENSSL_RAW_DATA, $iv);
        $calc  = hash_hmac(self::HASH, $crypt, $key, TRUE);

        // (bool|string) FALSE if decryption failed, decrypted string otherwise
        return hash_equals($hmac, $calc) ? $value : FALSE;
    }

    /**
     * See if a string has been automatically encrypted using the Vine.
     * ---
     * @param   string  String to evaluate.
     * @return  bool    TRUE if string has been encrypted using Vine, FALSE otherwise.
     */
    public static function isEncrypted($input)
    {
        // Prepare data
        $config = Vine_Registry::getConfig(Vine::CONFIG_CRYPT);
        $flag   = self::FLAG . $config['flag'];

        // Not even a valid string to work with
        if ( ! is_string($input) || strlen($input) < strlen($flag)) {
            return FALSE;
        }

        // Flag exists in encrypted data
        if (FALSE !== stripos($input, $flag, strlen($input) - strlen($flag))) {
            return TRUE;
        }

        // Flag not present, data may or may not be encrypted
        return FALSE;
    }

    /**
     * Securely hash a password.
     * ---
     * @param   string  Password to hash.
     * @return  string  Hashed password.
     */
    public static function passwordHash($pass)
    {
        return password_hash(trim($pass), PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Remove the automated flag from an encrypted string.
     * ---
     * @param   string  Encrypted string.
     * @return  string  Encrypted string without the flag.
     */
    private static function removeFlag($input)
    {
        $config = Vine_Registry::getConfig(Vine::CONFIG_CRYPT);
        $flag   = self::FLAG . $config['flag'];
        return substr($input, 0, strlen($input) - strlen($flag));
    }
}
