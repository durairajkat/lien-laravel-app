<?php

// Dependencies
require_once 'ConstantContact/ConstantContact.php';

/**
 * Wrapper class for communicating with the Constant Contact API.
 * @author  Tell Konkle
 * @date    2012-05-23
 * -
 * CHANGE LOG:
 * -
 * 2015-11-10 by Tell Konkle
 * Bugfix for generateAccessToken() not decoding username.
 * -
 * 2013-01-08 by Tell Konkle
 * Updated createContact() method.
 * -
 * 2012-05-28 by Tell Konkle
 * Added createContact() method.
 */
class Mailing_ConstantContact
{
    /**
     * OAuth URLs. Used for OAuth 2.0 authentication process.
     */
    const OAUTH = 'https://oauth2.constantcontact.com/oauth2/oauth/siteowner/authorize';
    const TOKEN = 'https://oauth2.constantcontact.com/oauth2/oauth/token';

    /**
     * Authorization and access credentials.
     * @var  string
     * @see  __construct()
     */
    protected $authType        = 'basic';
    protected $apiKey          = NULL;
    protected $apiSecret       = NULL;
    protected $username        = NULL;
    protected $passwordOrToken = NULL;

    /**
     * End-user error. Human-readable error.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * An instance of ConstantContact.
     * @var  object
     * @see  loadAccount() 
     */
    protected $api = NULL;

    /**
     * Class constructor.
     * @param   string
     * @param   string
     * @param   string  The authorization type (basic, oauth, or oauth2).
     * @return  void
     */
    public function __construct($apiKey, $apiSecret, $authType = 'basic')
    {
        try {
            // Standardize authorization type
            $authType = strtolower($authType);

            // Verify authorization type
            if ($authType != 'basic' && $authType != 'oauth' && $authType != 'oauth2') {
                throw new InvalidArgumentException('Argument 2 must be string with '
                        . 'value of "basic", "oauth", or "oauth2"');
            }

            // Save info
            $this->authType  = $authType;
            $this->apiKey    = $apiKey;
            $this->apiSecret = $apiSecret;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Initialize a connection to the Constant Contact API.
     * @param   string  The account's username.
     * @param   string  The account's password or access token (when using OAuth 1 or 2).
     * @return  bool    TRUE if account loaded successfully, FALSE otherwise.
     */
    public function loadAccount($username, $passwordOrToken)
    {
        // Set data
        $this->api = new ConstantContact(
            $this->authType,
            $this->apiKey,
            $username,
            $passwordOrToken
        );

        // Data set successfully
        return TRUE;
    }

    /**
     * Get a specified mailing list.
     * @param   mixed  The list's ID.
     * @return  object
     */
    public function getList($listId)
    {
        // None of the lists have been loaded yet, do so now
        if (empty($this->lists)) {
            $this->getLists();
        }

        // List found, return it
        if (isset($this->lists[$listId])) {
            return $this->lists[$listId];
        }

        // List could not be found
        return FALSE;
    }

    /**
     * Load all email lists associated with the loaded account.
     *
     * [!!!] Account must be loaded with loadAccount() prior to calling this method!
     *
     * @return bool|array  FALSE is no lists could be loaded or found, array otherwise.
     */
    public function getLists()
    {
        try {
            // Reset loaded lists
            $this->lists = array();

            // Something went wrong earlier, stop here
            if ( ! $this->isValid()) {
                return FALSE;
            }

            // Attempt to load the lists
            $response = $this->api->getLists();

            // This account has no lists
            if (count($response['lists']) <= 0) {
                return FALSE;
            }

            // Loop through and compile simple list
            foreach ($response['lists'] as $list) {
                $this->lists[$list->id] = $list;
            }

            // Return array of simplified lists
            return $this->lists;
        } catch (CTCTException $e) {
            $this->setError('Unable to load mailing lists.');
            return FALSE;
        }
    }

    /**
     * Get all of the lists for an account, formatted as a simple key => value array,
     * where the key represents the list ID, and the value is the list name.
     *
     * @return  array  Will be empty if no lists could be found or loaded.
     * @see     Vine_Form::setOptions()
     * @see     Vine_Form::getOptions()
     */
    public function getListOptions()
    {
        // None of the lists have been loaded yet, do so now
        if (empty($this->lists)) {
            $this->getLists();
        }

        // Start with an empty <option> list
        $lists = array();

        // Lists are still empty, stop here
        if (empty($this->lists)) {
            return $lists;
        }

        // Loop through and compile a simple key => value array of the lists
        foreach ($this->lists as $id => $list) {
            $lists[$id] = $list->name;
        }

        // Return the simple <option> array
        return $lists;
    }

    /**
     * Add a contact to a specified mailing list.
     * @param   string  The list ID to apply contact to.
     * @param   object  An instance of Mailing_CreateContact.
     * @return  bool    TRUE if contact added successfully, FALSE otherwise.
     */
    public function createContact($listId, Mailing_CreateContact $contact)
    {
        // Load desired list and generated contact info
        $list = $this->getList($listId);
        $data = $contact->getData();

        // List does not exist, failed
        if ( ! $list) {
            return FALSE;
        }

        // Contact already exists, assume successful
        if (is_array($this->api->searchContactsByEmail($data['email']))) {
            return TRUE;
        }

        // Compile contact parameters
        $params = array
        (
            'emailAddress' => $data['email'],
            'firstName'    => $data['first_name'],
            'lastName'     => $data['last_name'],
            'companyName'  => $data['company'],
            'homePhone'    => $data['phone'],
            'addr1'        => $data['address1'],
            'addr2'        => $data['address2'],
            'city'         => $data['city'],
            'stateName'    => $data['province'],
            'countryCode'  => $data['country'],
            'postalCode'   => $data['postal'],
        );

        // Create a Contact object
        $contactObj = new Contact($params);
        $contactObj->lists[] = $listId;

        // If instance of Contact is returned then addContact() successful
        if (is_object($this->api->addContact($contactObj))) {
            return TRUE;
        // If any value other than object, addContact() failed
        } else {
            return FALSE;
        }
    }

    /**
     * Get end-user error.
     * @return  string  Will be NULL if no error found.
     * @see     isValid()
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Has an error been set?
     * @return  bool
     * @see     getError()
     */
    public function isValid()
    {
        return NULL == $this->error;
    }

    /**
     * Get the username of the authorized Constant Contact account.
     * @return  string 
     */
    public function getUsername()
    {
        return $this->username;
    }
    
    /**
     * Get access token used for OAuth 2.0 authentication.
     * @return  string
     * @see     generateAccessToken()
     */
    public function getAccessToken()
    {
        return $this->passwordOrToken;
    }

    /**
     * Set an end-user, human-readable, error.
     * @param   string  The error message.
     * @return  void
     */
    public function setError($message)
    {
        $this->error = $message;
    }

    /**
     * Generate an access token inside a valid callback URL.
     * @return  void
     */
    public function generateAccessToken()
    {
        // Input for verification
        $req      = new Vine_Request();
        $code     = urldecode($req->get('code'));
        $user     = urldecode(urldecode($req->get('username')));
        $error    = urldecode($req->get('error'));
        $callback = Vine_Session::get('constant-contact-callback', TRUE);

        // Data is missing
        if ( ! $code || ! $user || ! $callback) {
            $this->setError('Insufficient data for verification. Please try again.');
            return;
        }

        // An error occurred on Constant Contact's side
        if ($error) {
            $this->setError($error);
            return;
        }

        /**
         * @author  Tell Konkle
         * @date    2012-05-23
         *
         * The official Constant Contact library has a class in Authentication.php called
         * CTCTOauth2, but the return value of getAccessToken() is broken, and for
         * forward compatibility, I've opted to just create my own equivilent solution.
         */

        // Compile request
        $url = self::TOKEN . '?grant_type=authorization_code'
             . '&client_id=' . $this->apiKey
             . '&client_secret=' . $this->apiSecret
             . '&code=' . $code
             . '&redirect_uri=' . urlencode($callback);

        // Execute request
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_HEADER, 0);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $url);
		$result = json_decode(curl_exec($handle), TRUE);
		curl_close($handle);

        // Error returned
        if (isset($result['error_description'])) {
            $this->setError($result['error_description']);
            return;
        // No error returned, but no access token returned either
        } elseif ( ! isset($result['access_token'])) {
            $this->setError('Insufficient data for verification. Please try again.');
            return;
        }

        // Parse request result
		$this->passwordOrToken = $result['access_token'];
        $this->username        = $user;
    }

    /**
     * Send the user to a ConstantContact OAuth 2.0 URL, where they can authorize this
     * application to access their account.
     *
     * The callback URL must be of the same origin in which current session data is being
     * stored.
     *
     * @param   string  The callback URL.
     * @return  void
     */
    public function goAuthorizeApp($callbackUrl)
    {
        header('location: ' . $this->getAuthorizeAppUrl($callbackUrl));
        exit(1);
    }

    /**
     * Get the OAuth 2.0 URL to redirect user to, in order to authorize application to
     * access their account.
     *
     * The callback URL must be of the same origin in which current session data is being
     * stored.
     *
     * @param   string  The callback URL.
     * @return  void
     * @see     goAuthorizeApp()
     */
    public function getAuthorizeAppUrl($callbackUrl)
    {
        // Save callback URL - @see generateAccessToken()
        $_SESSION['constant-contact-callback'] = $callbackUrl;

        // Compile and return OAuth 2.0 URL
        return self::OAUTH
            . '?response_type=code'
            . '&client_id=' . $this->apiKey
            . '&redirect_uri=' . urlencode($callbackUrl);
    }
}