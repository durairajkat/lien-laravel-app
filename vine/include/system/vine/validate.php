<?php

/**
 * Input Verification
 * ---
 * In a Vine_Action handle, this class is used as the "source" parameter (argument 3) for
 * Vine_Verify::setRules(). This class handles advanced input verification, particularly
 * when referencing and verifying against a database or other input fields.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Validate
{
    /**
     * An instance of Vine_Db.
     * ---
     * @var  object
     */
    protected $db = NULL;

    /**
     * Data to work with. Formatted as: [field => value]
     * ---
     * @var  array
     */
    protected $workspace = NULL;

    /**
     * Class constructor.
     * ---
     * ) Setup workspace (data array to work with).
     * ) Load Vine_Db instance via dependency injection or from registry.
     * ---
     * @param   array   Workspace data. Format as: field => value.
     * @param   object  [optional] Custom instance of Vine_Db.
     * @return  void
     */
    public function __construct($workspace, $db = NULL)
    {
        // Load custom instance of Vine_Db
        if (is_object($db) && ($db instanceof Vine_Db)) {
            $this->db = $db;
        // Load Vine_db from registry
        } else {
            $this->db = Vine_Registry::getObject(Vine::CONFIG_DB);
        }

        // Set workspace data and custom validation class
        $this->workspace = $workspace;
    }

    /**
     * Special validation rule. Verify that a value exists within in a specific database
     * table field.
     * ---
     * @param   mixed   The value to check.
     * @param   string  The table and field to check. Format as: table.field
     * @return  bool
     */
    public function fetch($value, $tableField)
    {
        try {
            // This helps developer identify bug in validation rule
            if ( ! is_string($tableField)) {
                throw new InvalidArgumentException('Invalid validation rule. Rule must '
                        . 'be a valid "table.field" format.');
            }

            // Get table & column data
            $data = explode('.', $tableField);

            // Rule is invalid
            if ( ! isset($data[0]) || ! isset($data[1])) {
                throw new InvalidArgumentException('Invalid validation rule. Rule must '
                        . 'be a valid "table.field" format.');
            }

            // Compile MySQL query
            $sql = "SELECT `" . $data[1] . "` "
                 . "FROM `" . $data[0] . "` "
                 . "WHERE `" . $data[1] . "` = ? "
                 . "LIMIT 1";

            // Return as bool
            return (bool) $this->db->fetch($sql, $value);
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Special validation rule. Check to see if the value of one field matches the value
     * of another. Useful for rapidly verifying password and email matches for form
     * validation.
     * ---
     * @param  mixed   The value
     * @param  string  The name of the field to check the value against.
     */
    public function matches($value, $field)
    {
        // Get the field's value
        $field = Vine_Array::getKey($this->workspace, $field);

        // (bool)
        return $value === $field;
    }
}
