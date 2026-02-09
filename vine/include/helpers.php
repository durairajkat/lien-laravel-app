<?php

/**
 * Application Helper Functions
 * ---
 * Globally accessible helper functions should be declared here. Use sparingly.
 */

if ( ! function_exists('ruleInside')) {
    /**
     * Shortcut for whitelist <option> validation in form actions.
     * ---
     * @param   string    Name of static method to call in the Options helper class.
     * @param   variadic  Arguments to pass to static method.
     * @return  string    'inside[foo,bar]' string rule.
     */
    function ruleInside($method, ...$args)
    {
        return 'inside[' . implode(',', array_keys(Options::{$method}(...$args))) . ']';
    }
}
