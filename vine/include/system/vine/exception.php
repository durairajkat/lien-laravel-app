<?php

/**
 * Error & Exception Handler
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Exception extends Exception
{
    /**
     * The paths to the HTML templates for displaying exceptions and errors.
     */
    const TPL_EXCEPTION = 'templates/display-exception.tpl';
    const TPL_ERROR     = 'templates/display-error.tpl';

    /**
     * Native PHP error bitmasks. Faster than using constants. Avoids if/else statements
     * to see what version of PHP is running (to know whether we can use the "deprecated"
     * and "recoverable" constants).
     * ---
     * @see  http://www.php.net/manual/en/errorfunc.constants.php
     */
    protected static $codes = array
    (
        1     => 'Fatal Error',
        2     => 'Warning',
        4     => 'Parse Error',
        8     => 'Notice',
        16    => 'Fatal Error',
        32    => 'Warning',
        64    => 'Fatal Error',
        128   => 'Warning',
        256   => 'Fatal Error',
        512   => 'Warning',
        1024  => 'Notice',
        2048  => 'Strict',
        4096  => 'Recoverable Error',
        8192  => 'Deprecated',
        16384 => 'Deprecated',
    );

    /**
     * Output errors and exceptions as plain text.
     * ---
     * @var  bool
     */
    private static $_textOnly = FALSE;

    /**
     * Handle a native PHP error.
     * ---
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    public static function error($code, $msg, $file, $line)
    {
        try {
            // (bitmasking) This error level has been supressed
            if ( ! (error_reporting() & $code)) {
                return;
            }

            // Save numeric version of the error code
            $bit = $code;

            // Human-readable error code
            if (isset(self::$codes[$code])) {
                $code = self::$codes[$code];
            // Error level/code not recognized
            } else {
                $code = 'Unknown';
            }

            // Simplify file path (if possible)
            $file = Vine_Debug::getCleanPath($file);

            // Display error
            if (ini_get('display_errors')) {
                // Output ajax or shell friendly error
                if (self::isTextOnly()) {
                    self::errorText($code, $msg, $file, $line);
                // Output regular error
                } else {
                    self::errorHtml($code, $msg, $file, $line);
                }
            // Tell user something went wrong so they don't get a white screen of death
            } elseif (in_array($bit, array(1, 16, 64, 256))) {
                echo Vine_Registry::getSetting(Vine::DEFAULT_ERROR);
            }

            // Log error
            Vine_Log::logError($code, $msg, $file, $line);
        // Halt script when exception is thrown inside error handler
        } catch (Exception $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Handle an exception.
     * ---
     * @param   object  An instance of an Exception or Error based class.
     * @return  void
     */
    public static function handle($e, $log = TRUE)
    {
        try {
            // Display exception
            if (ini_get('display_errors')) {
                // Output ajax or shell friendly exception
                if (self::isTextOnly()) {
                    self::exceptionText($e);
                // Output regular exception
                } else {
                    self::exceptionHtml($e);
                }
            }

            // Log exception (if applicable)
            if ($log) {
                Vine_Log::logException($e);
            }
        // Fatal exception
        } catch (Exception $e) {
            if (ini_get('display_errors')) {
                echo self::exceptionText($e);
            } exit;
        // Fatal error (PHP 7+ only)
        } catch (Error $e) {
            if (ini_get('display_errors')) {
                echo self::exceptionText($e);
            } exit;
        }
    }

    /**
     * Set exception and error handlers to output text-only errors rather than styled ones.
     * ---
     * @param   bool
     * @return  void
     */
    public static function setTextOnly($textOnly = TRUE)
    {
        self::$_textOnly = (bool) $textOnly;
    }

    /**
     * Display an HTML styled PHP error.
     * ---
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    protected static function errorHtml($code, $msg, $file, $line)
    {
        // Load display error template
        $tpl = self::_loadTpl(self::TPL_ERROR);

        // Display plain-text error if template failed to load
        if ( ! $tpl) {
            self::errorText($code, $msg, $file, $line);
            return;
        }

        // Search for template tags and replace with data
        $tags = array('%code%', '%message%', '%file%', '%line%');
        $data = array($code, $msg, $file, $line);

        // Display error
        echo str_replace($tags, $data, $tpl);
    }

    /**
     * Display a plain-text PHP error. The format is:
     * ---
     * ) [Level] message ~ file ~ line 00
     * ---
     * @param   int
     * @param   string
     * @param   string
     * @param   int
     * @return  void
     */
    protected static function errorText($code, $msg, $file, $line)
    {
        printf("\n[%s] %s ~ %s ~ line %d", $code, strip_tags($msg), $file, $line);
    }

    /**
     * Display an HTML styled exception.
     * ---
     * @param   object  An instance of an Exception based class.
     * @return  void
     */
    protected static function exceptionHtml($e)
    {
        // Load display exception template
        $tpl = self::_loadTpl(self::TPL_EXCEPTION);

        // Display plain-text exception if template failed to load
        if ( ! $tpl) {
            self::exceptionText($e);
            return;
        }

        // Get exception info
        $type  = get_class($e);
        $file  = Vine_Debug::getCleanPath($e->getFile());
        $line  = $e->getLine();
        $msg   = htmlspecialchars($e->getMessage());
        $trace = nl2br($e->getTraceAsString());

        // Search for template tags and replace with data
        $tags = ['%type%', '%file%', '%line%', '%message%', '%trace%'];
        $data = [$type, $file, $line, $msg, $trace];

        // Display exception
        echo str_replace($tags, $data, $tpl);
    }

    /**
     * Display plain-text exception. The format is:
     * ---
     * ) [Exception] message ~ file ~ line 00
     * ---
     * @param   object  An instance of an Exception based class.
     * @return  void
     */
    protected static function exceptionText($e)
    {
        // Get exception info
        $type = get_class($e);
        $msg  = $e->getMessage();
        $file = Vine_Debug::getCleanPath($e->getFile());
        $line = $e->getLine();

        // Display exception
        printf("\n[%s] %s ~ %s ~ line %d", $type, $msg, $file, $line);
    }

    /**
     * Determine whether or not to output an error or exception as text or HTML.
     * ---
     * @return  bool
     */
    protected static function isTextOnly()
    {
        return self::$_textOnly || Vine_Request::isAjax() || Vine_Request::isCli();
    }

    /**
     * Load an error or exception template.
     * ---
     * @param   string
     * @return  string
     */
    private static function _loadTpl($tpl)
    {
        // Path to template
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $tpl;

        // Verify template exists
        if ( ! is_file($path)) {
            throw new VineMissingFileException('Template not found: ' . $path);
        }

        // Load template file
        $tpl = @file_get_contents($path);

        // Template failed to load
        if (FALSE === $tpl) {
            throw new VinePermissionsException('Template not readable: ' . $path);
        }

        // The template data
        return $tpl;
    }
}
