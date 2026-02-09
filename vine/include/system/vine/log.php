<?php

/**
 * Line Logs, Debug Logs, Error Logs, Event Logs, Exception Logs
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Log
{
    /**
     * The date format for all logs.
     */
    const DATE_FORMAT = 'Y-m-d H:i:s';

    /**
     * The various log names and template paths.
     */
    const TPL_MANUAL    = 'templates/log-manual.tpl';
    const TPL_REVIEW    = 'templates/log-review.tpl';
    const TPL_DEBUG     = 'templates/log-debug.tpl';
    const TPL_ERROR     = 'templates/log-error.tpl';
    const TPL_EVENT     = 'templates/log-event.tpl';
    const TPL_EXCEPTION = 'templates/log-exception.tpl';

    /**
     * The permissions (modes) for log files and log directories.
     */
    const MODE_LOGS = 0777;
    const MODE_DIR  = 0777;

    /**
     * The log configuration. Usually loaded from Vine_Registry.
     * ---
     * @var  array
     */
    protected $config = [];

    /**
     * Manually log something to a single line on a specified path.
     * ---
     * @param   mixed   The data to log.
     * @param   string  The log's file name. Will save to default log directory.
     * @return  void
     */
    public static function logLine($data, $fileName)
    {
        try {
            // Auto-convert objects and arrays
            if (is_object($data) || is_array($data)) {
                $data = print_r($data, TRUE);
            }

            // (string) Normalize new lines
            $data = str_replace("\r\n", "\n", $data);

            // (string) Convert new lines "\n" to string '\n'
            $data = str_replace("\n", '\n', $data);

            // Log to a single line
            $log = new Vine_Log();
            $log->putLine($data, $fileName);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Manually log something to a specified path. Will auto-convert objects and arrays
     * to print_r().
     * ---
     * @param   mixed   The data to log.
     * @param   string  The log's file name. Will save to default log directory.
     * @return  void
     */
    public static function logManual($data, $fileName)
    {
        try {
            // Auto-convert objects and arrays
            if (is_object($data) || is_array($data)) {
                $data = print_r($data, TRUE);
            }

            // Log something
            $log = new Vine_Log();
            $log->putManual($data, $fileName);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Log a debugging result. Will auto-convert objects and arrays to print_r().
     * ---
     * @param   mixed
     * @param   array
     * @return  void
     */
    public static function logDebug($data, $config = NULL)
    {
        try {
            // Auto-convert objects and arrays
            if (is_object($data) || is_array($data)) {
                $data = print_r($data, TRUE);
            }

            // Log event
            $log = new Vine_Log($config);
            $log->putDebug($data);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Log data that needs reviewed. Will auto-convert objects and arrays to print_r().
     * ---
     * @param   mixed
     * @param   string
     * @param   array
     * @return  void
     */
    public static function logReview($data, $subject = 'No subject', $config = NULL)
    {
        try {
            // Auto-convert objects and arrays
            if (is_object($data) || is_array($data)) {
                $data = print_r($data, TRUE);
            }

            // Log event
            $log = new Vine_Log($config);
            $log->putReview($data, trim($subject));
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Log a native PHP error.
     * ---
     * @param   string
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    public static function logError($code, $msg, $file, $line)
    {
        try {
            $log = new Vine_Log();
            $log->putError($code, $msg, $file, $line);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Log an event.
     * ---
     * @param   mixed
     * @param   array
     * @return  void
     */
    public static function logEvent($message, $config = NULL)
    {
        try {
            // Event logs are strings, integers, and floats
            if (is_object($message) || is_array($message)) {
                throw new InvalidArgumentException('Argument 1 does not accept type: '
                        . gettype($message));
            }

            // One-liner pal, you do this our way (that's why it's called "framework")
            $message = str_replace(["\n", "\r"], '', $message);

            // Log event
            $log = new Vine_Log($config);
            $log->putEvent($message);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Log an exception.
     * ---
     * @param   object  An instance of an Exception based class.
     * @return  void
     */
    public static function logException($e)
    {
        try {
            $log = new Vine_Log();
            $log->putException($e);
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Class constructor. If no configuration is provided, the configuration will be
     * auto-loaded from the registry.
     * ---
     * @param   array
     * @return  void
     */
    public function __construct($config = NULL)
    {
        try {
            // Load log config from registry
            if (NULL === $config) {
                $this->config = Vine_Registry::getConfig(Vine::CONFIG_LOGS);
            // Use custom config
            } elseif (is_array($config)) {
                $this->config = $config;
            // Invalid custom config
            } else {
                throw new InvalidArgumentException('Argument 1 must be NULL or array.');
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save a log line.
     * ---
     * @param   string
     * @return  void
     */
    public function putLine($data, $fileName)
    {
        try {
            // Load save path
            $path = $this->getPath() . DIRECTORY_SEPARATOR . $fileName;

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save a manual log.
     * ---
     * @param   string
     * @return  void
     */
    public function putManual($data, $fileName)
    {
        try {
            // Load template
            $tpl  = $this->getTpl(self::TPL_MANUAL);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . $fileName;

            // Search & replace
            $search  = ['%date%', '%host%', '%data%'];
            $replace = [date(self::DATE_FORMAT), Vine_Request::getIp(), $data];
            $data    = str_replace($search, $replace, $tpl);

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save a debugging log.
     * ---
     * @param   string
     * @return  void
     */
    public function putDebug($data)
    {
        try {
            // Debugging logs are disabled, stop here
            if ( ! $this->config['debug']) {
                return;
            }

            // Load template
            $tpl  = $this->getTpl(self::TPL_DEBUG);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . 'debug.log';

            // Search & replace
            $search  = ['%date%', '%host%', '%data%'];
            $replace = [date(self::DATE_FORMAT), Vine_Request::getIp(), $data];
            $data    = str_replace($search, $replace, $tpl);

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save a review log.
     * ---
     * @param   string
     * @param   string
     * @return  void
     */
    public function putReview($data, $sub)
    {
        try {
            // Load template
            $tpl  = $this->getTpl(self::TPL_REVIEW);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . 'review.log';

            // Search & replace
            $search  = ['%date%', '%host%', '%data%', '%subject%'];
            $replace = [date(self::DATE_FORMAT), Vine_Request::getIp(), $data, $sub];
            $data    = str_replace($search, $replace, $tpl);

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save an error log.
     * ---
     * @param   string
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    public function putError($code, $msg, $file, $line)
    {
        try {
            // Error logs are disabled, stop here
            if ( ! $this->config['errors']) {
                return;
            }

            // Load template
            $tpl  = $this->getTpl(self::TPL_ERROR);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . 'errors.log';

            // Remove HTML from message
            $msg = strip_tags($msg);

            // Search & replace
            $search  = ['%date%', '%code%', '%message%', '%file%', '%line%'];
            $replace = [date(self::DATE_FORMAT), $code, $msg, $file, $line];
            $data    = str_replace($search, $replace, $tpl);

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save an event log.
     * ---
     * @param   string
     * @return  void
     */
    public function putEvent($msg)
    {
        try {
            // Event logs are disabled, stop here
            if ( ! $this->config['events']) {
                return;
            }

            // Load template
            $tpl  = $this->getTpl(self::TPL_EVENT);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . 'events.log';

            // Search & replace
            $search  = ['%date%', '%host%', '%message%'];
            $replace = [date(self::DATE_FORMAT), Vine_Request::getIp(), $msg];
            $data    = str_replace($search, $replace, $tpl);

            // Save log, set permissions
            $log = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Save an exception log.
     * ---
     * @param   object  An instance of an Exception or Error based class.
     * @return  void
     */
    public function putException($e)
    {
        try {
            // Exception logs are disabled, stop here
            if ( ! $this->config['exceptions']) {
                return;
            }

            // Load template and log path
            $tpl  = $this->getTpl(self::TPL_EXCEPTION);
            $path = $this->getPath() . DIRECTORY_SEPARATOR . 'exceptions.log';

            // Get exception info
            $date    = date(self::DATE_FORMAT);
            $host    = Vine_Request::getIp();
            $code    = $e->getCode();
            $type    = get_class($e);
            $message = $e->getMessage();
            $file    = Vine_Debug::getCleanPath($e->getFile());
            $line    = $e->getLine();
            $trace   = $e->getTraceAsString();

            // Template tags to search for
            $search = [
                '%date%', '%host%', '%type%', '%code%',
                '%file%', '%line%', '%message%', '%trace%',
            ];

            // Replace tags with data
            $replace = [
                $date, $host, $type, $code,
                $file, $line, $message, $trace,
            ];

            // Search & replace, save log, set permissions
            $data = str_replace($search, $replace, $tpl);
            $log  = file_put_contents($path, $data, FILE_APPEND);
            @chmod($path, self::MODE_LOGS);

            // Failed to log data
            if (FALSE === $log) {
                throw new VinePermissionsException('Unable to write log to: ' . $path);
            }
        // Don't log exception
        } catch (Exception $e) {
            Vine_Exception::handle($e, FALSE);
        // Don't log error (PHP 7+ only)
        } catch (Error $e) {
            Vine_Exception::handle($e, FALSE);
        }
    }

    /**
     * Get the directory path to save log files to (needed for date-based logs).
     * ---
     * @return  string
     */
    private function getPath()
    {
        // Get configuration (usually auto-loaded from the registry)
        $dir    = $this->config['dir'];
        $format = $this->config['file-dates'];

        // Verify directory exists
        if ( ! is_dir($dir)) {
            throw new VineMissingFileException('Directory ' . $dir . ' not found.');
        // Verify directory is writable
        } elseif ( ! is_writable($dir)) {
            throw new VinePermissionsException('Directory ' . $dir . ' not writable.');
        }

        // All logs are housed in a single directory with no sub-directories
        if (FALSE === strpos($format, '/')) {
            return $dir;
        }

        // Logs are stored by date
        $folders = explode('/', $format);

        // Start at base log directory
        $path = $dir;

        // Each array value is a nested sub-folder
        foreach ($folders as $folder) {
            // Convert folder to applicable date format and append to base directory
            $path .= DIRECTORY_SEPARATOR . date($folder);

            // Make folder if it doesn't exist already (common for date-based logs)
            if ( ! is_dir($path)) {
                @mkdir($path, self::MODE_DIR);
                @chmod($path, self::MODE_DIR);
            // Ensure path is writable
            } else {
                @chmod($path, self::MODE_DIR);
            }
        }

        // The full path to save logs to
        return $path;
    }

    /**
     * Get a log template.
     * ---
     * @param   string
     * @return  string
     */
    private function getTpl($tpl)
    {
        // Path to log template
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $tpl;

        // Verify template exists
        if ( ! is_file($path)) {
            throw new VineMissingFileException('Template not found: ' . $path);
        }

        // Load template file
        $tpl = file_get_contents($path);

        // Template failed to load
        if (FALSE === $tpl) {
            throw new VinePermissionsException('Template not readable: ' . $path);
        }

        // The template data
        return $tpl;
    }
}
