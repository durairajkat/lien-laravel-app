<?php

/**
 * Admin, primary record.
 * ---
 * @author  Tell Konkle
 * @date    2017-08-09
 */
class Model_Admin extends Model_Base
{
    /**
     * Load a record.
     * ---
     * @param   string  The WHERE statement.
     * @param   array   The query binds to attach to WHERE statement.
     * @return  bool
     */
    public function load($where)
    {
        // Get query bindings
        $args  = func_get_args();
        $binds = $this->getBinds($args);

        // Query this record
        $sql = "SELECT * "
             . "FROM admins "
             . "WHERE " . $where . " "
             . "AND deleted IS NULL "
             . "LIMIT 1";

        // Save data to object
        $this->data = $this->db->fetch($sql, $binds);

        // Loaded successfully if data is not empty
        return ! empty($this->data);
    }

    /**
     * [!!!] Delete the loaded record.
     * ---
     * @return  bool  FALSE if record cannot be deleted, TRUE otherwise.
     */
    public function delete()
    {
        // Record must be loaded
        if ( ! $this->isLoaded()) {
            return FALSE;
        }

        // Compile record update
        $data = [
            'status'  => 'Disabled',
            'deleted' => date('Y-m-d H:i:s'),
        ];

        // Update record
        $this->db->update('admins', $data, 'id = ?', 1, $this->get('id'));

        // Assume record was successfully updated
        return TRUE;
    }
}