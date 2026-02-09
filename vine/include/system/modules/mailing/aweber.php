<?php

// Dependencies
require_once 'AWeberApi/aweber_api.php';

/**
 * Wrapper class for communicating with the AWeber API.
 * @author  Tell Konkle
 * @date    2012-05-22
 * -
 * CHANGE LOG:
 * -
 * 2015-02-09 by Tell Konkle
 * Fixed bug in createContact() method (IP address).
 * -
 * 2013-03-19 by Tell Konkle
 * Fixed bug in createContact() method (exceptions getting thrown for existing contacts).
 * -
 * 2012-05-28 by Tell Konkle
 * Added createContact() method.
 * -
 * 2012-05-23 by Tell Konkle
 * Added getAuthorizeAppUrl() method.
 */
class Mailing_AWeber
{
    /**
     * An instance of AWeberAPI.
     * @var  object
     * @see  setAccess()
     */
    protected $aweber = NULL;

    /**
     * An instance of AWeberCollection.
     * @var  object
     * @see  setAccess()
     */
    protected $account = NULL;

    /**
     * Access token.
     * @var  string
     * @see  generateAccessToken()
     */
    protected $accessTokenKey    = NULL;
    protected $accessTokenSecret = NULL;

    /**
     * End-user error. Human-readable error.
     * @var  string
     * @see  setError()
     * @see  getError()
     */
    protected $error = NULL;

    /**
     * Active mailing lists for a loaded account.
     * @var  array
     */
    protected $lists = array();

    /**
     * Class constructor. Set application's AWeber access credentials.
     *
     * [!!!] Consumer = This Application
     *
     * @param   string
     * @param   string
     * @return  void
     */
    public function __construct($consumerKey, $consumerSecret)
    {
        $this->aweber = new AWeberAPI($consumerKey, $consumerSecret);
    }

    /**
     * Set access token used to safely access a specific user's AWeber account.
     * @param   string  The access token key.
     * @param   string  The access token secret.
     * @return  bool    TRUE if account loaded successfully, FALSE otherwise.
     */
    public function loadAccount($accessKey, $accessSecret)
    {
        try {
            // May be needed later
            $this->accessTokenKey    = $accessKey;
            $this->accessTokenSecret = $accessSecret;

            // Load specified account
            $this->account = $this->aweber->getAccount($accessKey, $accessSecret);
            return TRUE;
        } catch (AWeberException $e) {
            $this->setError('Unable to authorize access to AWeber user account.');
            return FALSE;
        }
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
        // Reset loaded lists
        $this->lists = array();

        // Something went wrong earlier, stop here
        if ( ! $this->isValid()) {
            return FALSE;
        }

        // This account has no lists, stop here
        if (count($this->account->lists->data['entries']) <= 0) {
            return FALSE;
        }

        // Loop through and sanitize lists
        foreach ( $this->account->lists->data['entries'] as $list) {
            $this->lists[$list['id']] = json_decode(json_encode($list));
        }

        // Return array of sanitized lists
        return $this->lists;
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
     * @todo    Improve method used to detect if contact already exists.
     */
    public function createContact($listId, Mailing_CreateContact $contact)
    {
        // Load desired list
        $list = $this->getList($listId);

        // List does not exist, failed
        if ( ! $list) {
            return FALSE;
        }

        // Attempt to create contact
        try {
            // Get all of the set data from generated contact
            $data = $contact->getData();

            // Compile contact parameters
            $params = array
            (
                'email'      => $data['email'],
                'name'       => $data['first_name'] . ' ' . $data['last_name'],
                'ip_address' => Vine_Request::getIp(),
            );

            // Create new subscriber
            $subscribers = $this->account->loadFromUrl($list->subscribers_collection_link);
            $subscribers->create($params);
            return TRUE;
        // Always thrown if contact already exists (no big deal)
        } catch (AWeberAPIException $e) {
            return TRUE;
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
     * Get access token key used for authentication.
     * @return  string
     * @see     generateAccessToken()
     * @see     loadAccount()
     */
    public function getAccessTokenKey()
    {
        return $this->accessTokenKey;
    }

    /**
     * Get access token secret used for authentication.
     * @return  string
     * @see     generateAccessToken()
     * @see     loadAccount()
     */
    public function getAccessTokenSecret()
    {
        return $this->accessTokenSecret;
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
        try {
            // Input for verification
            $req      = new Vine_Request();
            $token    = $req->get('oauth_token');
            $verifier = $req->get('oauth_verifier');
            $secret   = Vine_Session::get('aweber-secret');

            // Data is missing
            if ( ! $token || ! $verifier || ! $secret) {
                $this->setError('Insufficient data for verification. Please try again.');
                return;
            }

            // Pull the request token key and verifier code from the current URL
            $this->aweber->user->requestToken = $req->get('oauth_token');
            $this->aweber->user->verifier     = $req->get('oauth_verifier');

            // Retrieve the stored request token secret
            $this->aweber->user->tokenSecret = Vine_Session::get('aweber-secret', TRUE);

            // Exchange a request token with a verifier code for an access token.
            $accessTokenData         = $this->aweber->getAccessToken();
            $this->accessTokenKey    = $accessTokenData[0];
            $this->accessTokenSecret = $accessTokenData[1];
        } catch (AWeberException $e) {
            $this->setError('Unable to verify access token. Please try again.');
        }
    }

    /**
     * Redirect user to AWeber to authorize this application to access a user's AWeber
     * account.
     *
     * The callback URL must be of the same origin in which current session data is being
     * stored. The callback URL is where the application will exchange a request token
     * with a verifier code for an access token key and secret (which application should
     * store permanently).
     *
     * @param   string  The callback URL.
     * @return  void
     */
    public function goAuthorizeApp($callbackUrl)
    {
        // Get secret request token
        list($key, $secret) = $this->aweber->getRequestToken($callbackUrl);

        // Save token secret to session (will be used in callback URL)
        $_SESSION['aweber-secret'] = $secret;

        // Redirect to end-user authorization URL
        header('location: ' . $this->aweber->getAuthorizeUrl());
        exit(1);
    }

    /**
     * This method is identical to goAuthorizeApp(), except instead of handling the
     * redirection to the authorization URL, it returns the URL that the script should
     * redirect to as a string, and assumes developer will redirect manually. 
     *
     * @param   string  The callback URL.
     * @return  void
     * @see     goAuthorizeApp()
     */
    public function getAuthorizeAppUrl($callbackUrl)
    {
        // Get secret request token
        list($key, $secret) = $this->aweber->getRequestToken($callbackUrl);

        // Save token secret to session (will be used in callback URL)
        $_SESSION['aweber-secret'] = $secret;

        // Redirect to end-user authorization URL
        return $this->aweber->getAuthorizeUrl();
    }
}