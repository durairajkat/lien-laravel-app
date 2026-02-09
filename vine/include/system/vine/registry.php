<?php

/**
 * Application Registry
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
abstract class Vine_Registry
{
    /**
     * All of the registry's data.
     * ---
     * @var  array
     */
    private static $configs  = [];
    private static $objects  = [];
    private static $settings = [];

    /**
     * Get a configuration array from the registry.
     * ---
     * @param   string      The name of the array.
     * @return  bool|array  FALSE is returned if object is not found.
     */
    public static function getConfig($name)
    {
        if (isset(self::$configs[$name])) {
            return self::$configs[$name];
        } else {
            return FALSE;
        }
    }

    /**
     * Get an object from the registry.
     * ---
     * @param   string       The name of the object.
     * @return  bool|object  FALSE is returned if object is not found.
     */
    public static function getObject($name)
    {
        if (isset(self::$objects[$name])) {
            return self::$objects[$name];
        } else {
            return FALSE;
        }
    }

    /**
     * Get a setting from the registry.
     * ---
     * @param   string      The name of the setting.
     * @return  bool|mixed  FALSE is returned if setting is not found.
     */
    public static function getSetting($name)
    {
        if (isset(self::$settings[$name])) {
            return self::$settings[$name];
        } else {
            return FALSE;
        }
    }

    /**
     * Set a configuration array into the registry. Will NOT overwrite existing arrays.
     * ---
     * @param   string  The name of the array.
     * @param   array   The configuration array.
     * @return  void
     */
    public static function setConfig($name, array $config)
    {
        if ( ! isset(self::$configs[$name])) {
            self::$configs[$name] = $config;
        }
    }

    /**
     * Set a setting into the registry. Will NOT overwrite existing settings.
     * ---
     * @param   string  The name of the setting.
     * @param   array   The value of the setting.
     * @return  void
     */
    public static function setSetting($name, $value)
    {
        if ( ! isset(self::$settings[$name])) {
            self::$settings[$name] = $value;
        }
    }

    /**
     * Set an object into the registry. Will NOT overwrite existing objects.
     * ---
     * @param   string  The name of the object.
     * @param   object  The object to set.
     * @return  void
     */
    public static function setObject($name, $obj)
    {
        if ( ! isset(self::$objects[$name]) && is_object($obj)) {
            self::$objects[$name] = $obj;
        }
    }

    /**
     * See if a specified setting, config, or object exists.
     * ---
     * @param   string       The name to check.
     * @return  bool|string  FALSE, 'object', 'config', 'setting'
     */
    public static function exists($name)
    {
        if (isset(self::$objects[$name])) {
            return 'object';
        } elseif (isset(self::$configs[$name])) {
            return 'config';
        } elseif (isset(self::$settings[$name])) {
            return 'setting';
        } else {
            return FALSE;
        }
    }
}
