<?php

/**
 * The VinePHP framework is in maintenance-only LTS.
 * ---
 * @date  2016
 * @ends  2022
 */

/**
 * Vine Framework
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
abstract class Vine
{
    /**
     * The current version of the Vine Framework.
     */
    const VERSION = '2.5.1';

    /**
     * Global unicode character set (UCS-2, UTF-8, UTF-16, etc).
     */
    const UNICODE = 'UTF-8';

    /**
     * These constants are used throughout the Vine Framework to uniformly identify
     * settings and configuration arrays in the application's config files.
     */
    const DEFAULT_ERROR   = 'default-error';
    const TEST_MODE       = 'test-mode';
    const TEST_IP         = 'test-ip';
    const GLOBAL_HTTPS    = 'global-https';
    const GLOBAL_NOSNIFF  = 'global-nosniff';
    const TIMEZONE        = 'timezone';
    const DISPLAY_ERRORS  = 'display-errors';
    const CACHE_PATH      = 'cache-path';
    const CONFIG_LOGS     = 'logs';
    const CONFIG_DB       = 'db';
    const CONFIG_EMAILS   = 'emails';
    const CONFIG_SESSIONS = 'sessions';
    const CONFIG_CRYPT    = 'crypt';

    /**
     * Custom application-specific autoloader configuration.
     * ---
     * @var  array
     */
    private static $autoloadConfig = [];

    /**
     * A list of directories containing all loaded Vine Framework modules.
     * ---
     * @var  array
     */
    private static $moduleConfig = [];

    /**
     * Initialize a custom application-specific autoloader. Use NULL as a class name
     * prefix if the autoload directory is a "catch all."
     * ---
     * ) Each underscore ( _ ) in a class name will indicate a sub-directory.
     * ) This method should only be called once.
     * ---
     * @param   array  Format as: 'directory-path' => 'class-name-prefix'
     * @return  void
     */
    public static function setAutoload(array $config)
    {
        // No autoload paths specified, stop here
        if (empty($config)) {
            return;
        }

        // Sort configuration so NULL ("catch alls") are last
        arsort($config);

        // Save autoloader configuration
        self::$autoloadConfig = $config;

        // Register custom autoloader
        spl_autoload_register(['Vine', 'autoloadCustom']);
    }

    /**
     * Initialize the Vine Framework.
     * ---
     * @param   string  Configuration file path.
     * @param   string  Custom registry path.
     * @return  void
     */
    public static function start($configPath, $registryPath)
    {
        try {
            // Register the autoloader
            if ( ! spl_autoload_register(['Vine', 'autoloadVine'])) {
                throw new Exception('Autoloader failed.');
            // Verify that the developer config exists
            } elseif ( ! is_file($configPath)) {
                throw new Exception('Config path is invalid.');
            // Verify that the developer registry exists
            } elseif ( ! is_file($registryPath)) {
                throw new Exception('Registry path is invalid.');
            }

            // Get complete configuration file
            $config = self::getConfig($configPath);
        // Fatal exception
        } catch (Exception $e) {
            // Display raw exception
            if (ini_get('display_errors')) {
                echo 'Unable to initialize the Vine. ' . $e->getMessage();
            } die;
        }

        // Start the Vine
        self::loadExceptions();
        self::loadWorkspace($config);
        self::loadRegistry($config, $registryPath);
        self::loadModules();
    }

    /**
     * Shutdown function. Handle fatal errors.
     * ---
     * @return  void
     */
    public static function shutdown()
    {
        // Get the last error message
        if ($error = error_get_last()) {
            // Get error info
            $code  = $error['type'];
            $msg   = $error['message'];
            $file  = $error['file'];
            $line  = $error['line'];

            // Display and/or log fatal errors
            if (E_PARSE == $code || E_ERROR == $code || E_COMPILE_ERROR == $code) {
                // Discard all current output buffers
                while (ob_get_level()) {
                    ob_end_clean();
                }

                // Handle the error
                Vine_Exception::error($code, $msg, $file, $line);

                // Exit the script to avoid a loop
                exit(1);
            }
        }

        // Display debug dump if found
        echo Vine_Session::getDebug();
    }

    /**
     * Is the current client IP address a valid test IP?
     * ---
     * @return  bool  TRUE if current IP is a test IP, FALSE otherwise.
     */
    public static function testIp()
    {
        return in_array(
            Vine_Request::getIp(),
            (array) Vine_Registry::getSetting(self::TEST_IP)
        );
    }

    /**
     * Is application currently in test mode?
     * ---
     * @return  bool  TRUE if application is in test mode, FALSE otherwise.
     */
    public static function testMode()
    {
        return Vine_Registry::getSetting(self::TEST_MODE);
    }

    /**
     * Autoloader for application. Must be called via Vine::setAutoload().
     * ---
     * @param   string
     * @return  void
     */
    private static function autoloadCustom($class)
    {
        // Get autoloader configuration, all filenames should be lowercase
        $config = self::$autoloadConfig;
        $class  = strtolower($class);
        $path   = FALSE;

        // Loop throuh each autoload directory
        foreach ($config as $dir => $prefix) {
            // Standardize prefix
            $prefix = strtolower($prefix);

            // Class name doesn't apply to this prefix, skip to next item in loop
            if (strlen($prefix) && 0 !== strpos($class, $prefix)) {
                continue;
            }

            // Compile file path to class
            $path = rtrim($dir, '/\\') . '/'
                  . str_replace('_', '/', substr($class, strlen($prefix)))
                  . '.php';
            // Class found, stop loop
            if (file_exists($path)) {
                break;
            // Class not found
            } else {
                $path = FALSE;
            }
        }

        // Include class
        if (FALSE !== $path) {
            require_once $path;
        }
    }

    /**
     * Autoloader for Vine Framework modules.
     * ---
     * @param   string
     * @return  void
     */
    private static function autoloadModules($class)
    {
        // Get autoloader configuration, all filenames should be lowercase
        $config = self::$moduleConfig;
        $class  = strtolower($class);
        $path   = FALSE;

        // Loop throuh each autoload directory
        foreach ($config as $dir) {
            // The prefix for classes in a Vine module should be module's directory name
            $prefix = basename($dir);

            // Class name doesn't apply to this prefix, skip to next item in loop
            if (0 !== strpos($class, $prefix)) {
                continue;
            }

             // Compile file path to class
            $path = $dir
                  . str_replace('_', '/', substr($class, strlen($prefix)))
                  . '.php';

            // Class found, stop loop
            if (file_exists($path)) {
                break;
            // Class not found
            } else {
                $path = FALSE;
            }
        }

        // Include class
        if (FALSE !== $path) {
            require_once $path;
        }
    }

    /**
     * Autoloader for Vine Framework. Must be called via Vine::start().
     * ---
     * @param   string
     * @return  void
     */
    private static function autoloadVine($class)
    {
        // Only affects classes prefixed with: Vine_
        if (0 !== strpos($class, 'Vine_')) {
            return;
        }

        // Each underscore ( _ ) in a Vine's class name is a directory
        $path = VINE_PATH . str_replace('_', '/', strtolower($class)) . '.php';

        // Silently fail
        if ( ! file_exists($path)) {
            return;
        }

        // Include class
        require_once $path;
    }

    /**
     * Verify and setup the Vine_Registry.
     * ---
     * @param   string
     * @return  array
     */
    private static function getConfig($path)
    {
        // (string) Path to framework's default config
        $base = VINE_PATH . 'config.php';

        // Base config is to prevent E_NOTICE errors, but app config is still required
        if ( ! is_file($path)) {
            throw new Exception('Config path is invalid: ' . $path);
        }

        // (array) Merge base config with app config
        return Vine_Array::extend(TRUE, require $base, require $path);
    }

    /**
     * Load the exception definitions used throughout the Vine framework.
     * ---
     * @return  void
     */
    private static function loadExceptions()
    {
        require_once VINE_PATH . 'exceptions.php';
    }

    /**
     * Initialize an autoloader for all installed Vine Framework modules.
     * ---
     * @return  void
     */
    private static function loadModules()
    {
        // Get all subdirectories (i.e. modules) in module directory
        $mods = glob(VINE_MODULES . '*', GLOB_ONLYDIR);

        // Results found, autoload modules
        if ( ! empty($mods)) {
            // Save results
            self::$moduleConfig = $mods;

            // Register module autoloader
            spl_autoload_register(['Vine', 'autoloadModules']);
        }
    }

    /**
     * Verify and setup the Vine_Registry.
     * ---
     * @param   string
     * @param   string
     * @return  void
     */
    private static function loadRegistry($config, $path)
    {
        require VINE_PATH . 'registry.php';
        require $path;
    }

    /**
     * Apply the workspace settings of the application.
     * ---
     * @param   array
     * @return  void
     */
    private static function loadWorkspace($config)
    {
        require VINE_PATH . 'workspace.php';
    }
}
