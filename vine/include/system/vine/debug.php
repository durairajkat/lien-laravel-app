<?php

/**
 * Debugging & Unit Testing
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Debug
{
    /**
     * The path to the HTML template for displaying debugging data dump.
     */
    const TPL_DUMP = 'templates/display-dump.tpl';

    /**
     * Silently debug the framework. Useful for rapid development and production level
     * unit testing. Saves the results above to the debugging logs. Provides:
     * ---
     * ) POST data dump (prior to any sanitization).
     * ) GET data dump (prior to any sanitization).
     * ) COOKIE data dump (prior to any sanitization).
     * ) SESSION data dump.
     * ) Error handling status & details.
     * ) Request method.
     * ) IP address.
     * ) Timestamp.
     * ) PHP version.
     * ) Framework version.
     * ) Application encoding.
     * ---
     * @return  void
     */
    public static function debug()
    {
        // Prepare data
        $session = print_r($_SESSION, TRUE);
        $cookie  = print_r($_COOKIE, TRUE);
        $get     = print_r($_GET, TRUE);
        $post    = print_r($_POST, TRUE);
        $files   = print_r($_FILES, TRUE);
        $logCfg  = Vine_Registry::getConfig(Vine::CONFIG_LOGS);
        $ip      = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'localhost';

        // Compile debugging result
        $msg  = "SESSION:\n\n";
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
        $msg .= "Error Handling:\n\n";
        $msg .= "display-errors: " . (ini_get('display_errors') ? 'On' : 'Off') . "\n";
        $msg .= "error-reporting: " . error_reporting() . "\n";
        $msg .= "log-errors: " . ($logCfg['errors'] ? 'Yes' : 'No') . "\n";
        $msg .= "log-exceptions: " . ($logCfg['exceptions'] ? 'Yes' : 'No') . "\n";
        $msg .= "--\n";
        $msg .= "Miscellaneous Info:\n\n";
        $msg .= "Request Method: " . Vine_Request::getMethod() . "\n";
        $msg .= "IP Address: " . $ip . "\n";
        $msg .= "Timestamp: " . date('Y-m-d H:i:s') . "\n";
        $msg .= "PHP Version: " . phpversion() . "\n";
        $msg .= "Vine Version: " . Vine::VERSION . "\n";
        $msg .= "Encoding: " . Vine::UNICODE;

        // Log debugging result
        Vine_Log::logDebug($msg);
    }

    /**
     * Display a browser-friendly data dump. The data will be saved to the session, and
     * will be echoed to the browser when the framework's Vine::shutdown() function is
     * called after script execution is finished.
     * ---
     * @param   mixed  The data to dump and display.
     * @return  void
     */
    public static function dump($data)
    {
        try {
            // Path to template
            $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::TPL_DUMP;

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

            // Prepare data
            $data = is_string($data) ? $data : print_r($data, TRUE);
            $data = str_replace('%data%', $data, $tpl);

            // Save data to session
            Vine_Session::setDebug($data);
        } catch (VineFileException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Get a cleaned (and possibly simplified) version of a file's path.
     * ---
     * @param   string
     * @return  string
     */
    public static function getCleanPath($path)
    {
        // Simplify path to framework core, standardize directory separator
        if (0 === strpos($path, VINE_PATH)) {
            $path = '{vine}' . DIRECTORY_SEPARATOR . substr($path, strlen(VINE_PATH));
            $path = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $path);
        // Simplify path to application core, standardize directory separator
        } elseif (0 === strpos($path, INC_PATH)) {
            $path = '{include}' . DIRECTORY_SEPARATOR . substr($path, strlen(INC_PATH));
            $path = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $path);
        // Don't simplify path, standardize directory separator
        } else {
            $path = str_replace(array("\\", "/"), DIRECTORY_SEPARATOR, $path);
        }

        // (string) Cleaned path
        return $path;
    }
}
