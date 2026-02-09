<?php

// PEAR's XML_RPC2 package is not E_STICT compliant
error_reporting(E_ALL & ~E_STRICT);

// Dependencies
require_once 'XML/RPC2/Client.php';

/**
 * Wrapper class for communicating with the Benchmark Email API.
 * @author  Tell Konkle
 * @date    2012-05-25
 * -
 * CHANGE LOG:
 * -
 * 2012-05-28 by Tell Konkle
 * Added createContact() method.
 */
class Mailing_BenchmarkEmail
{
    /**
     * The URL used for communicating with the Benchmark Email API.
     */
    const API_URL = 'http://api.benchmarkemail.com/1.0';

    /**
     * An backend object instance created from XML_RPC2_Client.
     * @var  object
     * @see  loadAccount()
     */
    protected $api = NULL;

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
     * Access token, used for communicating with the API.
     * @var  string
     * @see  loadAccount()
     */
    protected $token = NULL;

    /**
     * Class constructor.
     * @return  void
     */
    public function __construct()
    {
        $this->api = XML_RPC2_Client::create(self::API_URL);
    }

    /**
     * Set Benchmark email access credentials.
     * @param   string  The Benchmark Email username.
     * @param   string  The Benchmark Email password.
     * @return  bool    TRUE if account loaded successfully, FALSE otherwise.
     */
    public function loadAccount($username, $password)
    {
        try {
            // Initialize connection backend
            $this->api = XML_RPC2_Client::create(self::API_URL);

            // Login user
            $this->token = $this->api->login($username, $password);

            // Successfully logged in
            return TRUE;
        } catch (XML_RPC2_FaultException $e) {
            $this->setError($e->getFaultString());
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
        try {
            // Reset loaded lists
            $this->lists = array();

            // Load lists
            $lists = $this->api->listGet($this->token, '', 1, 999, 'name', 'desc');

            // No lists could be found
            if (empty($lists)) {
                return FALSE;
            }

            // Loop through and compile simple list
            foreach ($lists as $list) {
                $this->lists[$list['id']] = $list;
            }

            // Return array of simplified lists
            return $this->lists;
        } catch (XML_RPC2_FaultException $e) {
            $this->setError($e->getFaultString());
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
            $lists[$id] = $list['listname'];
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
        try {
            // Get all of the set data from generated contact
            $data = $contact->getData();

            // Compile contact parameters
            $params = array
            (
                'email'     => $data['email'],
                'firstname' => $data['first_name'],
                'lastname'  => $data['last_name'],
            );

            // Attempt to create contact
            return (bool) $this->api->listAddContacts($this->token, $listId, $params);
        } catch (XML_RPC2_FaultException $e) {
            $this->setError($e->getFaultString());
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
     * Set an end-user, human-readable, error.
     * @param   string  The error message.
     * @return  void
     */
    public function setError($message)
    {
        $this->error = $message;
    }
}