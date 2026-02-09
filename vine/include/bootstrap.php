<?php

// [!!!] Required : Simplified directory separator
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

// [!!!] Required : Path to the root folder of the application
defined('ROOT_PATH') or define('ROOT_PATH', realpath(dirname(__FILE__) . '/../') . DS);

// [!!!] Required : Path the application uses for include/library/package files
defined('INC_PATH') or define('INC_PATH', dirname(__FILE__) . DS);

// [!!!] Required : Path to the Vine Framework's root folder
defined('VINE_PATH') or define('VINE_PATH', INC_PATH . 'system' . DS);

// [!!!] Required : Path to Vine Framework module's directory
defined('VINE_MODULES') or define('VINE_MODULES', VINE_PATH . 'modules' . DS);

// Dependencies required to initialize the Vine
require_once VINE_PATH . 'vine.php';
require_once VINE_PATH . 'helpers.php';
require_once INC_PATH  . 'helpers.php';
require_once INC_PATH  . 'libraries/Composer/autoload.php';

/**
 * Initialize the Vine Framework.
 * ---
 * @param   string  Configuration file path.
 * @param   string  Custom registry path.
 * @return  void
 */
Vine::start(INC_PATH . 'config.php', INC_PATH . 'registry.php');

/**
 * Initialize a custom application-specific autoloader. Use NULL as a class name prefix
 * if the autoload directory is a "catch all."
 * ---
 * ) Each underscore ( _ ) in a class name will indicate a sub-directory.
 * ) This method should only be called once.
 * ---
 * @param   array  ['/path/to/directory' => 'Prefix_']
 * @return  void
 */
Vine::setAutoload([
    INC_PATH . 'helpers' => NULL,
    INC_PATH . 'model'   => 'Model_'
]);
