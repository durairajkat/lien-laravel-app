<?php

/**
 * Macro Helper
 * ---
 * @author     Tell Konkle <tellkonkle@gmail.com>
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
trait Vine_Trait_Macro
{
    /**
     * Macros added to this class.
     * ---
     * @var  array
     */
    private static $macros = [];

    /**
     * Add a macro to this class.
     * ---
     * @param   string
     * @param   closure
     * @return  void
     */
    public static function macro($name, Closure $func)
    {
        self::$macros[$name] = $func;
    }

    /**
     * Try running a macro from a static context.
     * ---
     * @param   string
     * @param   array
     * @return  mixed
     */
    public static function __callStatic($method, $args)
    {
        // (mixed) A macro method exists in this trait's parent class
        if (isset(static::$macros[$method])) {
            $macro = static::$macros[$method]->bindTo(NULL, static::class);
            return $macro(...$args);
        }

        // Macro doesn't exist
        throw new Exception
        (static::class . '::' . $method . '() does not exist.');
    }

    /**
     * Try running a macro from an object context.
     * ---
     * @param   string
     * @param   array
     * @return  mixed
     */
    public function __call($method, $args)
    {
        // (mixed) A macro method exists in this trait's parent class
        if (isset(static::$macros[$method])) {
            $macro = static::$macros[$method]->bindTo($this, $this);
            return $macro(...$args);
        }

        // Macro doesn't exist
        throw new Exception
        (static::class . '::' . $method . '() does not exist.');
    }
}
