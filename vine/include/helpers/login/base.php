<?php

/**
 * @date    2019-04-01
 * @author  Tell Konkle
 * ---
 * Please use password_hash() and password_verify() in production. The hash_hmac() method is for
 * PHP <= 5.6 (which Vine no longer supports anyways).
 */

/**
 * Base class for logins for the entire application.
 * ---
 * @author  Tell Konkle
 * @date    2015-04-08
 */
abstract class Login_Base
{
    /**
     * Abstract methods. All child classes need to properly implement these methods.
     */
    abstract function doCookieLogin();
    abstract function doFormLogin($email, $password, $remember);
    abstract function doLogout();
    abstract function isLoggedIn();
    abstract function isNotLoggedIn();
    abstract function get($field);

    /**
     * Default URL and access denied message. May get overwritten.
     * ---
     * @var  string
     */
    protected $loginUrl     = NULL;
    protected $accessDenied = "Sorry, you don't have access to that area.";

    /**
     * Security keys (used alongside random salts in hashing for added security).
     * ---
     * @var  string
     */
    protected $loginKey    = NULL;
    protected $passwordKey = NULL;

    /**
     * Instance of Vine_Db.
     * ---
     * @var  object
     */
    protected $db = NULL;

    /**
     * Class constructor. Load Vine_Db instance via dependency injection or from registry.
     * @param   object  Instance of Vine_Db
     * @return  void
     */
    public function __construct($db = NULL)
    {
        // Load custom instance of Vine_Db
        if (is_object($db) && ($db instanceof Vine_Db)) {
            $this->db = $db;
        // Load Vine_db from registry
        } else {
            $this->db = Vine_Registry::getObject('db');
        }

        // Get security keys
        $this->loginKey    = Vine_Registry::getSetting('login-key');
        $this->passwordKey = Vine_Registry::getSetting('password-key');
    }

    /**
     * Set rules for script access. Rules are set as a simple string, with each rule being
     * a method of this class. Rules are delimited by a semi-colon. The URL is where the
     * page will redirect if the rules are not met. The URL is ignored for AJAX requests.
     * ---
     * @code
     *
     * // Redirect to "main" page if rules are not met
     * $login = new Login_User();
     * $login->setAccessRules('isLoggedIn;isActive', 'main');
     *
     * @endcode
     * ---
     * @param   string
     * @param   string
     * @param   bool|string  If FALSE, no message saved. If TRUE or NULL, default message
     *                       will be saved. If string, custom message will be saved.
     * @return  void
     */
    public function setAccessRules($rules, $url = NULL, $message = TRUE)
    {
        try {
            // Convert rules to array
            $rules = explode(';', trim($rules, ';'));

            // Loop through each rule
            foreach ($rules as $rule) {
                // Clean rule
                $rule = trim($rule);

                // Valid callback: native PHP or global function
                if (is_callable($rule)) {
                    $caller = NULL;
                // Valid callback: class method
                } elseif (is_callable(array($this, $rule))) {
                    $caller = $this;
                // Valid callback not found
                } else {
                    throw new BadMethodCallException('Rule "' . $rule . '" is not a '
                            . 'valid callback function.');
                }

                // Global function call
                if (NULL === $caller) {
                    $result = call_user_func_array($rule, array());
                // Class method call
                } else {
                    $result = call_user_func_array(array($caller, $rule), array());
                }

                // Rule violation, stop here
                if ( ! $result) {
                    // Handle AJAX violations less gracefully
                    if (Vine_Request::isAjax()) {
                        // For records
                        Vine_Log::logEvent('Access violation '
                                         . '(' . implode(', ', $rules) . ') '
                                         . 'on ' . basename($_SERVER['SCRIPT_NAME']));

                        // For JavaScript console
                        echo "Access Denied."; exit;
                    // Just do a nice and clean redirect
                    } else {
                        // URL to redirect to (defaults to login URL)
                        $url = NULL === $url ? $this->loginUrl : $url;

                        // Use custom message
                        if (is_string($message)) {
                            WscSession::setMessage(FALSE, $message);
                        // Use default message
                        } elseif (TRUE === $message || NULL === $message) {
                            WscSession::setMessage(FALSE, $this->accessDenied);
                        }

                        // Redirect
                        header('location: ' . $url); exit;
                    }
                }
            }
        } catch (BadMethodCallException $e) {
            Vine_Exception::handle($e);
        }
    }
}
