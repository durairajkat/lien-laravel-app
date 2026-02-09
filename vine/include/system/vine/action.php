<?php

/**
 * Action Handler
 * ---
 * In an application utilizing the Vine Framework, all create, update, and delete
 * functionality that is carried out by an end user should be processed through an action
 * inside of a Vine_Action handle.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Action extends Vine_Request
{
    /**
     * GET parameters to look for to identify what action to run, and whether or not to
     * force a JSON result.
     */
    const GET_ACTION = 'action';
    const GET_JSON   = 'json';

    /**
     * Parameter names in JSON encoded responses.
     */
    const JSON_DATA   = 'data';
    const JSON_ERRORS = 'errors';
    const JSON_MSG    = 'message';
    const JSON_STATUS = 'status';
    const JSON_URL    = 'url';
    const JSON_EXTRA  = 'extra';

    /**
     * For automatically generated error messages.
     */
    const ERROR_SMART = TRUE;
    const ERROR_TPL   = 'Invalid %field%.';
    const ERROR_TAG   = '%field%';

    /**
     * Specified files to include at the top of each action.
     * ---
     * @var  array
     */
    protected $prelude = NULL;

    /**
     * Script name of the action being ran.
     * ---
     * @var  string
     */
    protected $action = NULL;

    /**
     * Instances of Vine_Db and Vine_Validate, respectively.
     * ---
     * @var  object
     */
    protected $db       = NULL;
    protected $validate = NULL;

    /**
     * Action handle configuration.
     * ---
     * @var  array
     */
    protected $config = [
        'action-path'   => NULL,
        'default-url'   => NULL,
        'default-error' => "We're sorry, but your request could not be completed.",
        'root-url'      => NULL,
        'secure-ajax'   => FALSE,
        'test-mode'     => FALSE,
        'test-ip'       => NULL,
    ];

    /**
     * Action's input data, errors, temporary input data.
     * ---
     * @var  array
     */
    private $_data   = [];
    private $_errors = [];
    private $_tmp    = [];

    /**
     * Has debug() been ran? Has commit() been ran? Has cleanTmp() been ran?
     * ---
     * @var  bool
     */
    private $_debugged = FALSE;
    private $_commited = FALSE;
    private $_cleaned  = FALSE;

    /**
     * For debugging and unit testing. Output buffer from applicable action.
     * ---
     * @var  string
     */
    private $_output = NULL;

    /**
     * Data to use to run commit() method when finish() method is called. Used for
     * developers wishing to use the more descriptive committing methods in place of a
     * single commit() method.
     * ---
     * @var  array
     */
    private $_commit = [
        'status'        => FALSE,
        'message'       => NULL,
        'url-redirect'  => NULL,
        'ajax-redirect' => FALSE,
        'extra'         => FALSE,
    ];

    /**
     * Class constructor.
     * ---
     * ) Set action handle configuration.
     * ) Load Vine_Db instance via dependency injection or from registry.
     * ) Load instance of Vine_Validate.
     * ---
     * @param   array   The action handle configuration array.
     * @param   object  [optional] Custom instance of Vine_Db.
     * @return  void
     */
    public function __construct(array $config, $db = NULL)
    {
        // Call Vine_Request constructor
        parent::__construct();

        // Set the Vine's exception handler to output errors and exceptions as plain-text
        Vine_Exception::setTextOnly(TRUE);

        // Load custom instance of Vine_Db
        if (is_object($db) && ($db instanceof Vine_Db)) {
            $this->db = $db;
        // Load Vine_db from registry
        } else {
            $this->db = Vine_Registry::getObject(Vine::CONFIG_DB);
        }

        // Load config
        $this->config = array_merge($this->config, $config);

        // Load instance of Vine_Validate (request data, instance of Vine_Db)
        $this->validate = new Vine_Validate($this->input(), $this->db);
    }

    /**
     * Class destructor.
     * ---
     * ) Run debugger if applicable.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Save action result to debugging log, and email results if applicable
        if (   $this->config['test-mode']
            || $_SERVER['REMOTE_ADDR'] == $this->config['test-ip']
        ) {
            $this->debug();
        }

        // Ensure temporary data is cleared (safety net - usually run in other methods)
        $this->cleanTmp();
    }

    /**
     * Dynamically create a new object property in action handle.
     * ---
     * @param   string  A class name to load into action handle.
     * @param   string  The property name of the class in the action handle.
     * @param   array   Class constructor arguments.
     * @return  void
     */
    public function loadObject($class, $name, $args = NULL)
    {
        try {
            // Can't overwrite existing properties
            if (isset($this->$name)) {
                throw new VinePropertyException('Property "' . $name . '" cannot be '
                        . 'overwritten.');
            }

            // Load class constructor with constructor arguments
            if (NULL !== $args) {
                $reflection   = new ReflectionClass($class);
                $this->$name = $reflection->newInstanceArgs($args);
            // Load class without constructor arguments
            } else {
                $this->$name = new $class;
            }
        // Fatal exception
        } catch (VinePropertyException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Dynamically inject an object, array, string, float, or integer into action handle
     * and set as a property.
     * ---
     * @param   mixed   The item to inject. Type can vary.
     * @param   string  The property name to create.
     * @return  void
     */
    public function inject($item, $name)
    {
        try {
            // Can't overwrite existing properties
            if (isset($this->$name)) {
                throw new VinePropertyException('Property "' . $name . '" cannot be '
                        . 'overwritten.');
            }

            // Inject object
            $this->$name = $item;
        // Fatal exception
        } catch (VinePropertyException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Specify a file to include at the top of each action.
     * ---
     * @param   string  The file path.
     * @return  void
     */
    public function prelude($file)
    {
        try {
            // File not found
            if ( ! is_file($file)) {
                throw new VineMissingFileException('Prelude file ' . $file . ' not found');
            }

            // Add this file to prelude list
            $this->prelude[] = $file;
        } catch (VineMissingFileException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Run an action.
     * ---
     * @param   string  [optional] Manually run an action. Useful for cron/background scripts.
     * @return  void
     */
    public function run($action = NULL)
    {
        // Start a new output buffer
        ob_start();

        // Path to action folder
        $path = realpath($this->config['action-path']) . DIRECTORY_SEPARATOR;

        // Use action from $_GET parameter
        if (NULL === $action) {
            $this->action = $path . $this->get(self::GET_ACTION) . '.php';
        // Manually set action
        } else {
            $this->action = $path . $action . '.php';
        }

        // Invalid action request, save error message, redirect to default URL
        if ( ! is_file($this->action)) {
            $this->_handleFailed();
            return;
        }

        // At least one file needs to be included at the top of the action to run
        if (is_array($this->prelude) && ! empty($this->prelude)) {
            // Loop through and require each prelude file
            foreach ($this->prelude as $path) {
                require_once $path;
            }
        }

        // Run the applicable action
        require_once $this->action;
    }

    /**
     * Destroy all temporary request data.
     * ---
     * @param   bool  Mark action's input as being cleaned?
     * @return  void
     */
    public function cleanTmp($cleaned = FALSE)
    {
        // Has action already received final input cleaning? If so, stop here.
        if (TRUE === $this->_cleaned) {
            return;
        }

        // Destroy temporary data (e.g. credit card numbers) before saving to session
        if ( ! empty($this->_tmp)) {
            // Loop through and unset all temporary data
            foreach ($this->_tmp as $key) {
                $this->remove($key);
            }
        }

        // Final cleaning?
        $this->_cleaned = (bool) $cleaned;
    }

    /**
     * Commit the action, and don't process further.
     * ---
     * @param   bool    TRUE for success response, FALSE for error response.
     * @param   string  The success or error message to return.
     * @param   string  Redirect URL.
     * @param   bool    Redirect if request was made via AJAX?
     * @param   mixed   Extra data to send in AJAX response or direct request.
     * @return  void
     */
    public function commit(
        $status,
        $message,
        $url        = NULL,
        $ajaxDirect = FALSE,
        $extra      = FALSE
    ) {
        try {
            // Convert to boolean
            $status     = filter_var($status, FILTER_VALIDATE_BOOLEAN);
            $ajaxDirect = filter_var($ajaxDirect, FILTER_VALIDATE_BOOLEAN);

            // Verify status argument
            if ( ! is_bool($status)) {
                throw new InvalidArgumentException('Argument 1 must be a boolean.');
            }

            // Verify message argument
            if (NULL !== $message && ! is_string($message)) {
                throw new InvalidArgumentException('Argument 2 must be NULL or string.');
            }

            // Verify URL argument
            if (NULL !== $url && ! is_string($url)) {
                throw new InvalidArgumentException('Argument 3 must be NULL or string.');
            }

            // Save the output buffer
            $this->_output = ob_get_clean();

            // Action has been commited
            $this->_commited = TRUE;

            // Output as JSON result
            if (self::isAjax()) {
                $this->_handleJson($status, $message, $url, $ajaxDirect, $extra);
            // Handle redirect
            } else {
                $this->_handleDirect($status, $message, $url, $extra);
            }
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Test/production environment unit testing. Silently debug actions and the action
     * handler. Saves the results above to the debugging logs.
     * ---
     * ) Dump request type.
     * ) Dump request data.
     * ) Dump session data.
     * ) Dump action errors.
     * ) Dump output buffer.
     * ---
     * @return  void
     */
    public function debug()
    {
        // Destroy temporary data before dumping/logging it (i.e. credit card numbers)
        $this->cleanTmp();

        // Save the output buffer if it hasn't already been saved
        if (NULL === $this->_output) {
            $this->_output = ob_get_clean();
        }

        // Prepare data
        $action  = $this->action;
        $method  = strtoupper($this->method);
        $session = print_r($_SESSION, TRUE);
        $cookie  = print_r($this->cookie(), TRUE);
        $get     = print_r($this->get(), TRUE);
        $post    = print_r($this->post(), TRUE);
        $errors  = print_r($this->_errors, TRUE);
        $files   = print_r($_FILES, TRUE);

        // Compile debugging result
        $msg  = "Action: " . $action . "\n";
        $msg .= "Method: " . $method . "\n";
        $msg .= "--\n";
        $msg .= "SESSION:\n\n";
        $msg .= $session . "\n";
        $msg .= "--\n";
        $msg .= "COOKIE:\n\n";
        $msg .= $cookie . "\n";
        $msg .= "--\n";
        $msg .= "POST:\n\n";
        $msg .= $post . "\n";
        $msg .= "--\n";
        $msg .= "GET:\n\n";
        $msg .= $get . "\n";
        $msg .= "--\n";
        $msg .= "FILES:\n\n";
        $msg .= $files . "\n";
        $msg .= "--\n";
        $msg .= "Action Errors:\n\n";
        $msg .= $errors;
        $msg .= "--\n";
        $msg .= "Output Buffer:\n\n";
        $msg .= $this->_output;

        // Log debugging result
        Vine_Log::logDebug($msg);
    }

    /**
     * Check to see whether action contains any errors or not.
     * ---
     * @return  bool
     */
    public function isValid()
    {
        return empty($this->_errors);
    }

    /**
     * Set the status of the action.
     * ---
     * @param   bool  TRUE for success response, FALSE for error response.
     * @return  void
     */
    public function setStatus($status)
    {
        try {
            // Convert status to boolean
            $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);

            // Verify status argument
            if ( ! is_bool($status)) {
                throw new InvalidArgumentException('Argument 1 must be a boolean.');
            }

            // Save status for commit() later
            $this->_commit['status'] = $status;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Set the message to return.
     * ---
     * @param   string  The success or error message to return.
     * @return  void
     */
    public function setMessage($message)
    {
        try {
            // Verify message argument
            if (NULL !== $message && ! is_string($message)) {
                throw new InvalidArgumentException('Argument 1 must be NULL or string.');
            }

            // Save message for commit() later
            $this->_commit['message'] = $message;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Set the URL to redirect to when action is finished.
     * ---
     * @param   bool    Redirect if request was made via AJAX?
     * @param   string  Redirect URL.
     * @return  void
     */
    public function setRedirect($onAjax, $url)
    {
        try {
            // Verify URL argument
            if (NULL !== $url && ! is_string($url)) {
                throw new InvalidArgumentException('Argument 2 must be NULL or string.');
            }

            // Save redirect data for commit() later
            $this->_commit['ajax-redirect'] = $onAjax;
            $this->_commit['url-redirect']  = $url;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Set the URL to redirect to.
     * ---
     * @param   string  URL to redirect to.
     * @return  void
     */
    public function setUrl($url)
    {
        $this->_commit['ajax-redirect'] = $url;
        $this->_commit['url-redirect']  = $url;
    }

    /**
     * Set extra data to send when action is finished.
     * ---
     * @param   mixed  Extra data to send.
     * @return  void
     */
    public function setExtra($extra)
    {
        $this->_commit['extra'] = $extra;
    }

    /**
     * Run commit() using data from $_commit property.
     * ---
     * @return  void
     */
    public function finish()
    {
        // Get info from VineUI (v2)
        $success = $this->get('_success_redirect');
        $error   = $this->get('_error_redirect');

        // Use default behaviour
        if (FALSE === $success || FALSE === $error || ! self::isAjax()) {
            // Compile applicable info
            $status  = $this->_commit['status'];
            $message = $this->_commit['message'];
            $url     = $this->_commit['url-redirect'];
            $onAjax  = $this->_commit['ajax-redirect'];
            $extra   = $this->_commit['extra'];

            // Let commit() determine desired behaviour
            return $this->commit($status, $message, $url, $onAjax, $extra);
        // VineUI2 success behavior
        } elseif ($this->isValid()) {
            // Compile applicable info
            $status          = $this->_commit['status'];
            $message         = $this->_commit['message'];
            $url             = $this->_commit['url-redirect'];
            $extra           = $this->_commit['extra'];
            $this->_output   = ob_get_clean();
            $this->_commited = TRUE;

            // Goto URL specified in action handle
            if ('true' === $success) {
                return $this->_handleJson($status, $message, $url, TRUE, $extra);
            // Don't goto URL regardless of what action handle says
            } elseif ('false' === $success) {
                return $this->_handleJson($status, $message, FALSE, FALSE, $extra);
            // Goto URL specified by front-end regardless of what action handle says
            } else {
                return $this->_handleJson($status, $message, $success, TRUE, $extra);
            }
        // VineUI2 error behavior
        } else {
            // Compile applicable info
            $status          = $this->_commit['status'];
            $message         = $this->_commit['message'];
            $url             = $this->_commit['url-redirect'];
            $extra           = $this->_commit['extra'];
            $this->_output   = ob_get_clean();
            $this->_commited = TRUE;

            // Goto URL specified in action handle
            if ('true' === $error) {
                return $this->_handleJson($status, $message, $url, TRUE, $extra);
            // Don't goto URL regardless of what action handle says
            } elseif ('false' === $error) {
                return $this->_handleJson($status, $message, FALSE, FALSE, $extra);
            // Goto URL specified by front-end regardless of what action handle says
            } else {
                return $this->_handleJson($status, $message, $error, TRUE, $extra);
            }
        }
    }

    /**
     * Singular. Set an error. If only one argument is suppled, the error will not be tied
     * to a specific field. If two arguments are supplied, argument 1 should be the field
     * name, and argument 2 should be the error message.
     * ---
     * @param   string
     * @param   string
     * @return  void
     */
    public function setError()
    {
        try {
            // Arguments, argument count [faster than count($arg)]
            $arg = func_get_args();
            $num = func_num_args();

            // Verify arguments
            if ($num === 0 || $num > 2) {
                throw new InvalidArgumentException('1 or 2 arguments are required.');
            }

            // Error tied to specific field
            if ($num === 2) {
                $this->_errors[$arg[0]] = $arg[1];
            // Non-field error
            } else {
                $this->_errors[] = $arg[0];
            }
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Plural. Set multiple errors. Should be an array. If an error message applies to a
     * specific field, the applicable array key should be the field name.
     * ---
     * @param   array  The errors to set, with keys representing field names.
     * @return  void
     */
    public function setErrors(array $errors)
    {
        $this->_errors = array_merge($this->_errors, $errors);
    }

    /**
     * Set the required request method (POST or GET) for this action to be run with.
     * ---
     * @param   string  POST or GET
     * @param   string  Error message to use when request method is invalid.
     * @return  void
     */
    public function setRequiredMethod($method, $error = 'Invalid request method.')
    {
        // Standardize
        $method = strtoupper($method);

        // Valid request method, stop here
        if ($method === $this->method) {
            return;
        }

        // Commit action and redirect and/or set error message as applicable
        $this->commit(FALSE, $error, $this->config['default-url'], TRUE);
    }

    /**
     * Set a rule for a specified field.
     * ---
     * @param   string  The field to verify.
     * @param   string  The rules for this field. Use a semi-colon " ; " as a delimiter.
     * @param   string  Custom error message.
     * @return  void
     */
    public function setRules($field, $rules, $error = NULL)
    {
        // Get this field's value
        $value = $this->input($field);

        // Treat empty arrays (i.e. arrayed POST or GET fields) as empty strings
        if (is_array($value) && empty($value)) {
            $value = '';
        }

        // (recursion) Rules apply to multiple fields, use _setMultiRules(), stop here
        if (is_array($value)) {
            $this->_setMultiRules($value, $field, $rules, $error);
            return;
        }

        // Verify this field (field data, field rules, instance of Vine_Validate)
        $result = Vine_Verify::doRules($value, $rules, $this->validate);

        // Stop here, everything is good
        if (TRUE === $result) {
            return;
        }

        // Generate and set smart error
        if (NULL === $error) {
            $this->setError($field, $this->_makeError($field));
        // Set developer error
        } else {
            $this->setError($field, $error);
        }
    }

    /**
     * Set a request field as temporary, which will destroy an input field's data prior
     * to it getting saved to session in the event of a redirect. Mostly used to destroy
     * credit card info and passwords prior to a redirect.
     * ---
     * This method, when properly utilized, aids in PCI compliancy by preventing
     * sensitive field values from being dumped into debugging logs and session data.
     * ---
     * @param   string  The field to set as temporary.
     * @return  void
     */
    public function setTmp($field)
    {
        $this->_tmp[] = $field;
    }

    /**
     * Get the URL to the root view directory for this action handle. Sometimes useful
     * shortcut for URL generation.
     * ---
     * @return  string
     */
    public function url()
    {
        return rtrim($this->config['root-url'], '/\\') . '/';
    }

    /**
     * See if action handle is in test mode.
     * ---
     * @return  bool  TRUE if in test mode, FALSE otherwise.
     */
    public function inTestMode()
    {
        return $this->config['test-mode']
            || $_SERVER['REMOTE_ADDR'] == $this->config['test-ip']
            || Vine_Registry::getSetting(Vine::TEST_MODE);
    }

    /**
     * Recursively set multiple rules for a bracketed/array field. Recursion continues
     * until $value is no longer an array.
     * ---
     * @param   mixed
     * @param   string
     * @param   string
     * @param   string
     * @return  void
     */
    private function _setMultiRules($data, $field, $rules, $error)
    {
        // This field has sub-fields
        if (is_array($data)) {
            // (recursion) Loop through each sub-field
            foreach ($data as $value) {
                $this->_setMultiRules($value, $field, $rules, $error);
            }

            // Stop here
            return;
        }

        // Verify this field (field data, field rules, instance of Vine_Validate)
        $result = Vine_Verify::doRules($data, $rules, $this->validate);

        // Stop here, everything is good
        if (TRUE === $result) {
            return;
        }

        // Generate and set smart error
        if (NULL === $error) {
            $this->setError($field, $this->_makeError($field));
        // Set developer error
        } else {
            $this->setError($field, $error);
        }
    }

    /**
     * For invalid action requests.
     * ---
     * @return  void
     */
    private function _handleFailed()
    {
        // Compile error info
        $status  = FALSE;
        $message = $this->config['default-error'];
        $url     = $this->config['default-url'];

        // When request was made via AJAX
        if (self::isAjax()) {
            $this->_handleJson($status, $message, $url, TRUE, FALSE);
        // When request was not made via AJAX
        } else {
            $this->_handleDirect($status, $message, $url, FALSE);
        }
    }

    /**
     * For ajax requests. Output a JSON result with status, errors, and messages.
     * ---
     * @param   bool
     * @param   string
     * @param   string
     * @param   bool
     * @param   mixed
     * @return  void
     */
    private function _handleJson($status, $message, $url, $redirect, $extra)
    {
        // Don't redirect on error for an AJAX request
        if (FALSE === $redirect) {
            $url = 0;
        }

        // Compile JSON result (use 0/1 booleans whenever data not applicable)
        $json = [
            self::JSON_STATUS => FALSE === $status     ? 0 : 1,
            self::JSON_MSG    => NULL === $message     ? 0 : $message,
            self::JSON_ERRORS => empty($this->_errors) ? 0 : $this->_errors,
            self::JSON_URL    => $url,
            self::JSON_EXTRA  => $extra,
        ];

        // Tell AJAX JavaScript to redirect, save message to session
        if (TRUE === $redirect) {
            // Save error or success message to session
            if (0 !== $json[self::JSON_MSG]) {
                Vine_Session::setMessage($status, $message);
            }

            // Save error data to session
            if ( ! empty($this->_errors)) {
                Vine_Session::setErrors($this->_errors);
            }
        }

        // Save the output buffer if it hasn't already been saved
        if (NULL === $this->_output) {
            $this->_output = ob_get_clean();
        }

        // Send secure AJAX headers
        if ($this->config['secure-ajax']) {
            Vine_Security::headers();
        }

        // JSON result
        Vine_Json::putHeaders();
        echo Vine_Json::encode($json); exit;
    }

    /**
     * For non-ajax requests. Save applicable data, errors, and messages to session, and
     * redirect to commited URL.
     * ---
     * @param   bool
     * @param   string
     * @param   string
     * @param   mixed
     * @return  void
     */
    private function _handleDirect($status, $message, $url, $extra)
    {
        // Action failed, handle error
        if (FALSE === $status) {
            // Redirect, if applicable
            if (NULL !== $url) {
                // Destroy temporary data
                $this->cleanTmp(TRUE);

                // Save data and errors to session
                Vine_Session::setData($this->input());
                Vine_Session::setExtra($extra);
                Vine_Session::setErrors($this->_errors);

                // Save error message
                if (NULL !== $message) {
                    Vine_Session::setMessage(FALSE, $message);
                }

                // Redirect
                header('location: ' . $url); exit;
            // Output plain-text error message (separate from rest of output buffer)
            } else {
                $this->_output = ob_get_clean();
                echo $message; exit;
            }
        // Action successful, handle success
        } else {
            // Redirect, if applicable
            if (NULL !== $url) {
                // Save success message
                if (NULL !== $message) {
                    Vine_Session::setMessage(TRUE, $message);
                }

                // Save extra data
                Vine_Session::setExtra($extra);

                // Redirect
                header('location: ' . $url); exit;
            // Output plain-text success message (separate from rest of output buffer)
            } else {
                $this->_output = ob_get_clean();
                echo $message; exit;
            }
        }
    }

    /**
     * Auto-generate an error message for a specific field name.
     * ---
     * @param   string
     * @return  string
     */
    private function _makeError($name)
    {
        // Attempt to generate a human readable field name
        if (TRUE === self::ERROR_SMART) {
            $name = trim(preg_replace('/(?<=\\w)(?=[A-Z]{1,1}[a-z])/', " $1", $name));
            $name = ucwords(str_replace('_', ' ', $name));
        }

        // Generate and return auto-error message
        return str_replace(self::ERROR_TAG, $name, self::ERROR_TPL);
    }
}
