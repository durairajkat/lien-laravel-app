<?php

/**
 * PHP Session Handler
 * ---
 * Custom session handler. Supports native file-level sessions, as well as MySQL database
 * level sessions for large applications running across multiple servers. Sessions are
 * always started in the registry, after all other registry settings have been applied and
 * loaded.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Session
{
    /**
     * Session key that is used store all data and messages set inside Vine_Session. This
     * helps avoid naming conflicts, especially with third party libraries.
     * ---
     * $_SESSION['_vine']['message-error'] = 'Something bad';
     */
    const KEY_VINE        = '_vine';
    const KEY_DATA        = 'data';
    const KEY_EXTRA       = 'extra';
    const KEY_DEBUG       = 'debug';
    const KEY_ERRORS      = 'errors';
    const KEY_MSG_ERROR   = 'message-error';
    const KEY_MSG_SUCCESS = 'message-success';
    const KEY_TOKEN       = 'security-token';
    const KEY_GMT_OFFSET  = 'gmt-offset';

    /**
     * For database level sessions. Table's field names, engine, and charset.
     */
    const CHARSET  = 'utf8';
    const DATA     = 'data';
    const ENGINE   = 'InnoDB';
    const ID       = 'session_id';
    const MODIFIED = 'modified';

    /**
     * When using database session handler. An instance of Vine_Db. Auto-loaded from the
     * Vine_Registry, based on session configuration.
     * ---
     * @var  object
     */
    private $_db = NULL;

    /**
     * TRUE when session is opened/alive, FALSE otherwise.
     * ---
     * @var  bool
     */
    private $_opened = FALSE;

    /**
     * Database table name to store session data inside of.
     * ---
     * @var  string
     */
    private $_table = NULL;

    /**
     * Singleton design pattern.
     * ---
     * @var  object
     */
    private static $_instance = NULL;

    /**
     * Class constructor. Can only be called from Vine_Session::getInstance().
     * ---
     * ) Set session config or get session config from registry.
     * ) Apply session config to singleton object.
     * ---
     * @param   array  [optional] A valid session configuration array.
     * @return  void
     */
    private function __construct($config = NULL)
    {
        // Load session config from the registry
        if (NULL === $config || ! is_array($config) || empty($config)) {
            $config = Vine_Registry::getConfig(Vine::CONFIG_SESSIONS);
        }

        // Use database session handler
        if (TRUE === $config['db-enable']) {
            // Get Vine_Db instance and save applicable settings
            $this->_db    = Vine_Registry::getObject($config['db-registry']);
            $this->_table = $config['db-table'];

            // Set this class as the session handler
            session_set_save_handler(
                array(&$this, '_open'),
                array(&$this, '_close'),
                array(&$this, '_read'),
                array(&$this, '_write'),
                array(&$this, '_destroy'),
                array(&$this, '_clean')
            );

            // TEST MODE ONLY: Create session table if it doesn't exist (extra query)
            if (TRUE === Vine_Registry::getSetting(Vine::TEST_MODE)) {
                $this->_createTable();
            }
        }

        // Apply session settings
        session_cache_limiter(FALSE);
        session_set_cookie_params(
            0,
            $config['path'],
            $config['domain'],
            $config['secure'],
            $config['httponly']
        );

        // Start the session and load session's initial data if necessary
        $this->_start();
    }

    /**
     * Class destructor. Called during last reference or during shutdown sequence. Ensures
     * session gets written and closed.
     * ---
     * @return void
     */
    public function __destruct()
    {
        if (TRUE === $this->_opened) {
            session_write_close();
            $this->_opened = FALSE;
        }
    }

    /**
     * Singleton design pattern. Create single instance of Vine_Session.
     * ---
     * @param   array  [optional] Valid session configuration array.
     * @return  object
     */
    public static function getInstance($config = NULL)
    {
        // New instance, call class constructor
        if ( ! isset(self::$_instance)) {
            self::$_instance = new Vine_Session($config);
        }

        // Return object instance
        return self::$_instance;
    }

    /**
     * Manually run garbage collection on database session data. Only affects database
     * controlled sessions.
     * ---
     * @return  void
     */
    public function doGarbageCollection()
    {
        if (NULL !== $this->_db) {
            $this->_clean(ini_get('session.gc_maxlifetime'));
        }
    }

    /**
     * Get a specified session key.
     * ---
     * @param   string  Key name.
     * @param   bool    [optional] Destroy key once accessed (flash)? Default = FALSE.
     * @return  mixed
     */
    public static function get($key, $flash = FALSE)
    {
        // Grab key (will be FALSE if key not found
        $value = Vine_Array::getKey($_SESSION, $key);

        // Destroy key (if applicable)
        if ($flash) {
            Vine_Array::unsetKey($_SESSION, $key);
        }

        // The value of key
        return $value;
    }

    /**
     * Get data from session.
     * ---
     * @param   bool   [optional] Clear data once retrieved? Default = TRUE.
     * @return  array  Empty if no data found.
     */
    public static function getData($clear = TRUE)
    {
        // Get data
        $data = $_SESSION[self::KEY_VINE][self::KEY_DATA];

        // Clear data
        if (TRUE === $clear) {
            $_SESSION[self::KEY_VINE][self::KEY_DATA] = [];
        }

        // Return data array or FALSE
        return $data;
    }

    /**
     * Get extra data from session.
     * ---
     * @param   bool   [optional] Clear data once retrieved? Default = TRUE.
     * @return  mixed  FALSE if no extra data found.
     */
    public static function getExtra($clear = TRUE)
    {
        // Get extra data
        $extra = $_SESSION[self::KEY_VINE][self::KEY_EXTRA];

        // Clear extra data
        if (TRUE === $clear) {
            $_SESSION[self::KEY_VINE][self::KEY_EXTRA] = FALSE;
        }

        // Return extra data or FALSE
        return $extra;
    }

    /**
     * Get debugging data from session.
     * ---
     * @param   bool   [optional] Clear debugging data once retrieved? Default = TRUE.
     * @return  mixed  Empty string if no debugging data found.
     */
    public static function getDebug($clear = TRUE)
    {
        // Get debugging data
        $debug = $_SESSION[self::KEY_VINE][self::KEY_DEBUG];

        // Clear debugging data
        if (TRUE === $clear) {
            $_SESSION[self::KEY_VINE][self::KEY_DEBUG] = '';
        }

        // Return data or FALSE
        return $debug;
    }

    /**
     * Get multiple, usually field-specific, errors from session.
     * ---
     * @param   bool   [optional] Clear errors once retrieved? Default = TRUE.
     * @return  array  Empty if no errors found.
     */
    public static function getErrors($clear = TRUE)
    {
        // Get errors
        $errors = $_SESSION[self::KEY_VINE][self::KEY_ERRORS];

        // Clear errors
        if (TRUE === $clear) {
            $_SESSION[self::KEY_VINE][self::KEY_ERRORS] = [];
        }

        // Return errors array or FALSE
        return $errors;
    }

    /**
     * Get an error or success message from session. Messages are retrieved in the
     * following array format:
     * ---
     * [
     *      'status'  => TRUE or FALSE, (success or error)
     *      'message' => 'message text',
     * ];
     * ---
     * @param   bool        [optional] Clear message once retrieved? Default = TRUE.
     * @return  bool|array  FALSE if no messages, array otherwise.
     */
    public static function getMessage($clear = TRUE)
    {
        // Get error message
        if ($_SESSION[self::KEY_VINE][self::KEY_MSG_ERROR]) {
            // Get error
            $error = $_SESSION[self::KEY_VINE][self::KEY_MSG_ERROR];

            // Clear error
            if (TRUE === $clear) {
                $_SESSION[self::KEY_VINE][self::KEY_MSG_ERROR] = FALSE;
            }

            // Return error
            return array('status' => FALSE, 'message' => $error);
        // Get success message
        } elseif ($_SESSION[self::KEY_VINE][self::KEY_MSG_SUCCESS]) {
            // Get success
            $success = $_SESSION[self::KEY_VINE][self::KEY_MSG_SUCCESS];

            // Clear success
            if (TRUE === $clear) {
                $_SESSION[self::KEY_VINE][self::KEY_MSG_SUCCESS] = FALSE;
            }

            // Return success
            return array('status' => TRUE, 'message' => $success);
        // No messages
        } else {
            return FALSE;
        }
    }

    /**
     * Get user's timezone (GMT offset). Defaults to system if user's timezone not found.
     * ---
     * @return  string
     */
    public static function getUserZone()
    {
        return $_SESSION[self::KEY_VINE][self::KEY_GMT_OFFSET]
             ? $_SESSION[self::KEY_VINE][self::KEY_GMT_OFFSET]
             : date('T');
    }

    /**
     * Get anti-CSRF token stored for this session.
     * ---
     * @return  string
     */
    public static function getToken()
    {
        return $_SESSION[self::KEY_VINE][self::KEY_TOKEN];
    }

    /**
     * Save data array to session.
     * ---
     * @param   array
     * @return  void
     */
    public static function setData(array $data)
    {
        $_SESSION[self::KEY_VINE][self::KEY_DATA] = $data;
    }

    /**
     * Save extra data to session.
     * ---
     * @param   mixed
     * @return  void
     */
    public static function setExtra($extra)
    {
        $_SESSION[self::KEY_VINE][self::KEY_EXTRA] = $extra;
    }

    /**
     * Save debugging data to session.
     * ---
     * @param   mixed
     * @return  void
     */
    public static function setDebug($debug)
    {
        $_SESSION[self::KEY_VINE][self::KEY_DEBUG] = $debug;
    }

    /**
     * Save error array to session.
     * ---
     * @param   array
     * @return  void
     */
    public static function setErrors(array $errors)
    {
        $_SESSION[self::KEY_VINE][self::KEY_ERRORS] = $errors;
    }

    /**
     * Save an error or success message to the session.
     * ---
     * @param   bool    TRUE for success, FALSE for error
     * @param   string  Applicable message. Object or array can be passed for debugging.
     * @return  void
     */
    public static function setMessage($status, $message)
    {
        try {
            // Convert status to boolean
            $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);

            // Verify there's a success/error boolean status to work with
            if ( ! is_bool($status)) {
                throw new InvalidArgumentException('Argument 1 should be a boolean.');
            }

            // Developer may set an array|object into session message for debugging
            if (is_array($message) || is_object($message)) {
                $message = print_r($message, TRUE);
            }

            // Save error message to session
            if (FALSE === $status) {
                $_SESSION[self::KEY_VINE][self::KEY_MSG_ERROR] = $message;
            // Save success message to session
            } else {
                $_SESSION[self::KEY_VINE][self::KEY_MSG_SUCCESS] = $message;
            }
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Set anti-CSRF token stored for this session.
     * ---
     * @param   string
     * @return  void
     */
    public static function setToken($token)
    {
        $_SESSION[self::KEY_VINE][self::KEY_TOKEN] = $token;
    }

    /**
     * Set user's timezone (GMT offset).
     * ---
     * @param   string  Time zone name or GMT offset.
     * @return  string
     */
    public static function setUserZone($offset)
    {
        // Convert America/New_York type name to GMT offset (i.e. -18000)
        if ( ! is_numeric($offset)) {
            $from   = new DateTimeZone('UTC');
            $to     = new DateTimeZone($offset);
            $offset = $to->getOffset(new DateTime('now', $to));
        }

        // Save GMT offset
        $_SESSION[self::KEY_VINE][self::KEY_GMT_OFFSET] = $offset;
    }

    /**
     * See if an error or success message exists and needs to be displayed.
     * ---
     * @return  bool  TRUE if an error or success message is ready to be displayed.
     */
    public static function messageExists()
    {
        return FALSE !== self::getMessage(FALSE);
    }

    /**
     * Start the session.
     * ---
     * @return  void
     */
    private function _start()
    {
        // Start session, mark session as being started
        session_start();
        $this->_opened = TRUE;

        // Load session's initial data if it doesn't already exist
        if ( ! isset($_SESSION[self::KEY_VINE])) {
            $_SESSION[self::KEY_VINE] = [
                self::KEY_DATA        => [],
                self::KEY_EXTRA       => FALSE,
                self::KEY_DEBUG       => '',
                self::KEY_ERRORS      => [],
                self::KEY_MSG_ERROR   => FALSE,
                self::KEY_MSG_SUCCESS => FALSE,
                self::KEY_TOKEN       => Vine_Security::makeToken(TRUE),
                self::KEY_GMT_OFFSET  => FALSE,
            ];
        }
    }

    /**
     * For database level sessions. For rapid development purposes. Create the database
     * session table if it doesn't already exist.
     * ---
     * This method should only be called when the application is in test mode for
     * development, as it's an otherwise completely unnecessary and slow query to be
     * running all of the time.
     * ---
     * @return  void
     */
    private function _createTable()
    {
        // Compile query to generate the session table if it doesn't already exist
        $sql = "CREATE TABLE IF NOT EXISTS `" . $this->_table . "` ( "
             . "`" . self::ID . "` CHAR(40) NOT NULL, "
             . "`" . self::DATA . "` LONGTEXT NOT NULL, "
             . "`" . self::MODIFIED. "` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00', "
             . "PRIMARY KEY(`" . self::ID . "`) "
             . ") "
             . "ENGINE = " . self::ENGINE . " "
             . "DEFAULT CHARSET = " . self::CHARSET;

        // Execute query
        $this->_db->query($sql);
    }

    /**
     * Session garbage collection.
     * ---
     * @param   int
     * @return  bool
     */
    public function _clean($expired)
    {
        // Minimum timestamp all sessions must have without getting deleted
        $expired = date('Y-m-d H:i:s', time() - (int) $expired);

        // Compile garbage collection query
        $sql = "DELETE FROM `" . $this->_table . "` "
             . "WHERE `" . self::MODIFIED . "` < ?";

        // (bool) Execute query
        return $this->_db->query($sql, $expired);
    }

    /**
     * Close the session.
     * ---
     * @return  bool
     */
    public function _close()
    {
        return TRUE;
    }

    /**
     * Destroy the session from database. Unset all session variables.
     * ---
     * @param   string
     * @return  bool
     */
    public function _destroy($sid)
    {
        // Compile delete query
        $sql = "DELETE FROM `" . $this->_table . "` "
             . "WHERE `" . self::ID . "` = ? "
             . "LIMIT 1";

        // Execute query
        $deleted = $this->_db->query($sql, $sid);

        // Reset session
        $_SESSION = [];

        // (bool)
        return $deleted;
    }

    /**
     * Open the session.
     * ---
     * @return  bool
     */
    public function _open()
    {
        return TRUE;
    }

    /**
     * Retrieve session data from database.
     * ---
     * @param   string
     * @return  string
     */
    public function _read($sid)
    {
        // Compile fetch query
        $sql = "SELECT `" . self::DATA . "` "
             . "FROM `" . $this->_table . "` "
             . "WHERE `" . self::ID . "` = ? "
             . "LIMIT 1";

        // Execute query
        $data = $this->_db->fetch($sql, $sid);

        // Return empty string if no data
        if (FALSE === $data) {
            return '';
        // Return data
        } else {
            return (string) $data[self::DATA];
        }
    }

    /**
     * Write session data to database.
     * ---
     * @param   string
     * @param   string
     * @return  bool
     */
    public function _write($sid, $data)
    {
        // Current timestamp
        $stamp = date('Y-m-d H:i:s');

        // Compile INSERT/UPDATE query
        $sql = "INSERT INTO `" . $this->_table . "` "
             . "(`" . self::ID . "`, `" . self::DATA . "`, `" . self::MODIFIED . "`) "
             . "VALUES (?, ?, ?) "
             . "ON DUPLICATE KEY "
             . "UPDATE `" . self::DATA . "` = ?, `" . self::MODIFIED . "` = ? ";

        // (bool) Execute query
        return $this->_db->query($sql, $sid, $data, $stamp, $data, $stamp);
    }
}
