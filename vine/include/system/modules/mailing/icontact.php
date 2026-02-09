<?php

// Dependencies
require_once 'iContact/lib/iContactApi.php';

/**
 * Wrapper class for communicating with the iContact API.
 * @author  Tell Konkle
 * @date    2012-05-23
 * -
 * CHANGE LOG:
 * -
 * 2012-05-28 by Tell Konkle
 * Added createContact() method.
 */
class Mailing_iContact
{
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
     * An instance of iContactApi.
     * @var  object
     * @see  loadAccount() 
     */
    protected $api = NULL;

    /**
     * Set iContact access credentials.
     * @param   string  The app ID.
     * @param   string  The iContact username.
     * @param   string  The iContact password.
     * @return  bool    TRUE if account loaded successfully, FALSE otherwise.
     */
    public function loadAccount($appId, $username, $password)
    {
        // Set data
        $this->api = iContactApi::getInstance()->setConfig(array(
            'apiUsername' => $username,
            'apiPassword' => $password,
            'appId'       => $appId,
        ));

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
            if (count($response) <= 0) {
                return FALSE;
            }

            // Loop through and compile simple list
            foreach ($response as $list) {
                $list->id = $list->listId;
                $this->lists[$list->id] = $list;
            }

            // Return array of simplified lists
            return $this->lists;
        } catch (Exception $e) {
            // Get all of the errors from the API
            $errors = $this->api->getErrors();

            // Save the first error from the API
            if ($errors) {
                $this->setError($errors[0]);
            // Use a default error
            } else {
                $this->setError('Unable to load iContact campaign lists.');
            }

            // Failed
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
        try {
            // Get all of the set data from generated contact
            $data = $contact->getData();

            // Create contact object
            $contact = $this->api->addContact(
                $data['email'],
                NULL,
                NULL,
                $data['first_name'],
                $data['last_name'],
                NULL,
                $data['address1'],
                $data['address2'],
                $data['city'],
                $data['province'],
                $data['postal'],
                $data['phone'],
                $data['fax'],
                $data['company']
            );

            // Add new contact to specified list
            $this->api->subscribeContactToList($contact->contactId, $listId, 'normal');

            // Successful
            return TRUE;
        } catch (Exception $e) {
            // Get all of the errors from the API
            $errors = $this->api->getErrors();

            // Save the first error from the API
            if ($errors) {
                $this->setError($errors[0]);
            }

            // Failed
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