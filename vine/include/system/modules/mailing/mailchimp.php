<?php

// Dependencies
require_once 'MailChimp/mcapi_rpc.php';

/**
 * Wrapper class for communicating with the MailChimp API.
 * @author  Tell Konkle
 * @date    2015-08-14
 * -
 * CHANGE LOG:
 * -
 * 2015-09-25 by Tell Konkle
 * Added getContact() method.
 * -
 * 2015-08-14 by Tell Konkle
 * Added optional double opt-in functionality for createContact().
 * -
 * 2012-07-25 by Tell Konkle
 * Updated loadAccount() method.
 * -
 * 2012-05-28 by Tell Konkle
 * Added createContact() method.
 */
class Mailing_MailChimp
{
    /**
     * OAuth URLs. Used for OAuth 2.0 authentication process.
     */
    const AUTH_URL  = 'https://login.mailchimp.com/oauth2/authorize';
    const TOKEN_URL = 'https://login.mailchimp.com/oauth2/token';
    const META_URL  = 'https://login.mailchimp.com/oauth2/metadata';

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
     * Client ID and Client Secret. When using OAuth 2.0 authentication.
     * @var  string
     * @see  __construct()
     */
    protected $clientId     = NULL;
    protected $clientSecret = NULL;

    /**
     * API Key or access token (OAuth 2.0).
     * @var  string
     * @see  loadAccount()
     * @see  generateAccessToken()
     */
    protected $apiKeyOrToken = NULL;

    /**
     * Class constructor. Set client ID and client secret (when implementing OAuth 2.0
     * authentication.
     *
     * @param   string
     * @param   string
     * @return  void
     */
    public function __construct($clientId = NULL, $clientSecret = NULL)
    {
        $this->clientId     = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Initialize a connection to the MailChimp API.
     * @param   string  The API Key or access token.
     * @return  bool    TRUE if account loaded successfully, FALSE otherwise.
     */
    public function loadAccount($apiKeyOrToken)
    {
        // Attempt to load account
        $this->api = new mcapi_rpc($apiKeyOrToken);

        // Failed to load account
        if (mcapi_rpc_error::isError($this->api)) {
            $this->setError($this->api->getErrorMessage());
            return FALSE;
        }

        // Account loaded successfully
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
        // Reset loaded lists
        $this->lists = array();

        // Something went wrong earlier, stop here
        if ( ! $this->isValid()) {
            return FALSE;
        }

        // Attempt to load the lists
        $response = $this->api->lists();

        // Failed ot load lists
        if (mcapi_rpc_error::isError($response)) {
            $this->setError($response->getErrorMessage());
            return FALSE;
        }

        // No lists in account
        if (empty($response)) {
            return FALSE;
        }

        // Loop through and compile simple list
        foreach ($response as $list) {
            $this->lists[$list['id']] = json_decode(json_encode($list));
        }

        // Return array of simplified lists
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
     * See if specified email address is subscribed to specified mailing list.
     * @param   string      The list ID to search under.
     * @param   string      The email address to search for.
     * @return  bool|array  FALSE if email not found in list, array otherwise.
     */
    public function getContact($listId, $email)
    {
        // Attempt to load contact
        $data = $this->api->listMemberInfo($listId, $email);

        // Failed to load contact
        if (mcapi_rpc_error::isError($data)) {
            $this->setError($data->getErrorMessage());
            return FALSE;
        }

        // Contact info
        return $data;
    }

    /**
     * Remove specified email address from specified mailing list.
     * @param   string  The list ID to search under.
     * @param   string  The email address to unsubscribe.
     * @return  bool    This method ignores errors and always returns TRUE.
     */
    public function removeContact($listId, $email)
    {
        $this->api->listUnsubscribe($listId, $email, TRUE, FALSE, FALSE);
        return TRUE;
    }

    /**
     * Add a contact to a specified mailing list.
     * @param   string  The list ID to apply contact to.
     * @param   object  An instance of Mailing_CreateContact.
     * @param   bool    (opt) Use double opt-in functionality? Defaults to FALSE.
     * @return  bool    TRUE if contact added successfully, FALSE otherwise.
     */
    public function createContact($listId, Mailing_CreateContact $contact, $optIn = FALSE)
    {
        // Get all of the set data from generated contact
        $data = $contact->getData();

        // Compile contact parameters
        $params = array
        (
            'FNAME'   => $data['first_name'],
            'LNAME'   => $data['last_name'],
            'date'    => date('Y-m-d'),
            'phone'   => $data['phone'],
            'website' => $data['website'],
        );

        // Compile address only if all applicable address fields are present
        if (   isset($data['address1'])
            && isset($data['city'])
            && isset($data['province'])
            && isset($data['postal'])
            && isset($data['country'])
        ) {
            $params['address'] = array
            (
                'addr1'   => $data['address1'],
                'addr2'   => $data['address2'],
                'city'    => $data['city'],
                'state'   => $data['province'],
                'zip'     => $data['postal'],
                'country' => $data['country'],
            );
        }

        // Attempt to create contact
        $create = $this->api->listSubscribe(
            $listId, $data['email'], $params, 'html',
            (bool) $optIn, TRUE, TRUE, FALSE
        );

        // Failed to create contact
        if (mcapi_rpc_error::isError($create)) {
            $this->setError($create->getErrorMessage());
            return FALSE;
        }

        // Successful
        return TRUE;
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
     * Get access token used for OAuth 2.0 authentication.
     * @return  string
     * @see     generateAccessToken()
     */
    public function getAccessToken()
    {
        return $this->apiKeyOrToken;
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
        $code     = $req->get('code');
        $callback = Vine_Session::get('mailchimp-callback', TRUE);

        // Data is missing
        if ( ! $code) {
            $this->setError('Invalid verification code. Please try again.');
            return;
        }

        // Compile request
        $data = 'grant_type=authorization_code'
              . '&client_id=' . $this->clientId
              . '&client_secret=' . $this->clientSecret
              . '&code=' . $code
              . '&redirect_uri=' . urlencode($callback);

        // Get access token
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, self::TOKEN_URL);
		curl_setopt($handle, CURLOPT_HEADER, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($handle, CURLOPT_POST, 1);
		curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
		$token = json_decode(curl_exec($handle), TRUE);
		curl_close($handle);

        // Invalid response
        if ( ! isset($token['access_token'])) {
            $this->setError('Unable to generate access token. Invalid response.');
            return;
        }

        // Custom headers for cURL request
        $headers = array('Authorization: OAuth ' . $token['access_token']);

        // Get datacenter information
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, self::META_URL);
		curl_setopt($handle, CURLOPT_HEADER, 0);
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($handle, CURLOPT_POST, 0);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
		$meta = json_decode(curl_exec($handle), TRUE);
		curl_close($handle);

        // Invalid response
        if ( ! isset($meta['dc'])) {
            $this->setError('Unable to acquire datacenter string.');
            return;
        }

        // Save access token
        $this->apiKeyOrToken = $token['access_token'] . '-' . $meta['dc'];
    }

    /**
     * Send the user to a MailChimp OAuth 2.0 URL, where they can authorize this
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
        $_SESSION['mailchimp-callback'] = $callbackUrl;

        // Compile and return OAuth 2.0 URL
        return self::AUTH_URL
            . '?response_type=code'
            . '&client_id=' . $this->clientId
            . '&redirect_uri=' . urlencode($callbackUrl);
    }
}