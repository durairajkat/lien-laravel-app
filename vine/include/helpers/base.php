<?php

/**
 * Base abstract helper.
 * ---
 * @author  Tell Konkle
 * @date    2016-04-11
 */
abstract class Base
{
    /**
     * Instance of Vine_Db.
     * ---
     * @var  object
     */
    protected $db = NULL;

    /**
     * Class constructor. Load Vine_Db instance via injection or from registry.
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
}