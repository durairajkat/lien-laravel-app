<?php

/**
 * Base model abstract class.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-09
 */
abstract class Model_Base
{
    /**
     * Instance of Vine_Db.
     * ---
     * @var  object
     */
    protected $db = NULL;

    /**
     * Loaded record data.
     * ---
     * @var  array
     */
    protected $data = [];

    /**
     * Class constructor. Load Vine_Db instance via dependency injection or from registry.
     * ---
     * @param   object  Instance of Vine_Db.
     * @return  void
     */
    public function __construct($db = NULL)
    {
        // Load custom instance of Vine_Db
        if (is_object($db) && ($db instanceof Vine_Db)) {
            $this->db = $db;
        // Load Vine_db from registry
        } else {
            $this->db = Vine_Registry::getObject('db');
        }
    }

    /**
     * Get specific field from loaded data.
     * ---
     * @param   string  (opt) The field name. If not provided, all data is returned.
     * @param   bool    (opt) Escape data for HTML output?
     * @return  mixed   FALSE if field name not found, or data not loaded.
     */
    public function get($field = NULL, $escape = FALSE)
    {
        if ( ! is_array($this->data)) {
            return FALSE;
        } elseif (NULL === $field) {
            return $escape ? Vine_Html::output($this->data) : $this->data;
        } else {
            return $escape
                ? Vine_Html::output(Vine_Array::getKey($this->data, $field))
                : Vine_Array::getKey($this->data, $field);
        }
    }

    /**
     * See if data has been loaded into the model.
     * ---
     * @return  bool  TRUE if data has been loaded into the model, FALSE otherwise.
     */
    public function isLoaded()
    {
        return ! empty($this->data);
    }

    /**
     * Prepare query binds (merges multiple function arguments into a single array).
     * ---
     * @param   array  The arguments passed to the parent method.
     * @param   int    Argument to start binding on.
     * @return  array
     */
    protected function getBinds($args, $start = 1)
    {
        // Start with an empty array
        $binds = [];

        // Start at specified parent method argument
        for ($i = $start; $i < count($args); $i++) {
            // An array of binds
            if (is_array($args[$i])) {
                $binds = array_merge($binds, $args[$i]);
            // A single bind
            } else {
                $binds[] = $args[$i];
            }
        }

        // A single array of bindings
        return $binds;
    }
}
