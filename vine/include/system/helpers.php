<?php

/**
 * FRAMEWORK DEPENDENCY - AVOIDING MODIFYING IF POSSIBLE!
 * ---
 * @date  2018-05-04
 */

if ( ! function_exists('logger')) {
    /**
     * Log debugging data.
     * ---
     * @param   variadic  Data to log.
     * @return  void
     */
    function logger(...$args)
    {
        Vine_Log::logDebug($args);
    }
}

if ( ! function_exists('emailer')) {
    /**
     * Email debugging data.
     * ---
     * @param   variadic  Data to log.
     * @return  void
     */
    function emailer(...$args)
    {
        $data   = print_r($args, TRUE);
        $config = Vine_Registry::getConfig('emails');
        $email  = new Email();
        $email->setBody('<pre>' . $data . '</pre>', TRUE);
        $email->setTo($config['test-email']);
        $email->setSubject('Debugging Data');
        $email->send();
    }
}

if ( ! function_exists('dumper')) {
    /**
     * Dump debugging data.
     * ---
     * @param   variadic  Data to dump.
     * @return  void
     */
    function dumper(...$args)
    {
        $css = 'padding:1em;'
             . 'margin:0;'
             . 'position:relative;'
             . 'top:0;left:0;'
             . 'background:#ffffff;'
             . 'z-index:9999;';

        if (1 === count($args)) {
            echo '<pre style="' . $css . '">' . print_r($args[0], TRUE) . '</pre>';
        } else {
            echo '<pre style="' . $css . '">' . print_r($args, TRUE) . '</pre>';
        }
    }
}
