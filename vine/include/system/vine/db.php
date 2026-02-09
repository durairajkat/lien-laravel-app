<?php

/**
 * PDO Database Wrapper
 * ---
 * A simplistic PDO database wrapper, designed specifically for MySQL databases. Used
 * primarily for simplifying common insert, update, and fetch functionalities. Allows
 * developer full and direct access PDO handle if necessary.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Db
{
    /**
     * PDO database handle.
     * ---
     * @var  object
     */
    public $handle = NULL;

    /**
     * The number of rows affected by the last executed query.
     * ---
     * @var  int
     */
    protected $affectedRows = 0;

    /**
     * Configuration array.
     * ---
     * @var  array
     */
    protected $config = [];

    /**
     * Crypt configuration array.
     * ---
     * @var  array
     */
    protected $crypt = [];

    /**
     * The ID of the last inserted row.
     * ---
     * @var  int
     */
    protected $insertId = 0;

    /**
     * The last query that was executed.
     * ---
     * @var  string
     */
    protected $lastQuery = NULL;

    /**
     * Prepare query binds (merges multiple function arguments into a single array).
     * ---
     * @param   array  The arguments passed to the parent method.
     * @param   int    Argument to start binding on.
     * @return  array  Query binds.
     */
    public static function toBinds($args, $start = 0)
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

    /**
     * Class constructor. Load database and crypt configuration.
     * ---
     * @param   array  An array containing the connection info.
     * @param   array  [optional] An array containing the crypt configuration.
     * @return  void
     */
    public function __construct(array $config, $crypt = NULL)
    {
        $this->config = $config;
        $this->crypt  = $crypt ? $crypt : Vine_Registry::getConfig(Vine::CONFIG_CRYPT);
    }

    /**
     * Connect to database using loaded configuration. Clear config once connected.
     * ---
     * @return  void
     */
    public function connect()
    {
        try {
            // Already connected, stop here
            if (NULL !== $this->handle) {
                return;
            }

            // Setup PDO handle
            $this->handle = new PDO(
                $this->prepDsn($this->config),
                $this->config['user'],
                $this->config['pass']
            );

            // Allow PDO to throw exceptions
            $this->handle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Fatal exception
        } catch (PDOException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Close the current database connection.
     * ---
     * @return  void
     */
    public function close()
    {
        $this->handle = NULL;
    }

    /**
     * Fetch a single result set (row).
     * ---
     * @param   string      SQL query.
     * @param   variadic    [optional] Query binds. Can be arrays, arguments, or both.
     * @return  bool|array  FALSE if result not found, array otherwise.
     */
    public function fetch($sql, ...$args)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Get query bindings
            $binds = $this->prepBinds($args, 0);

            // Save query
            $this->lastQuery = $sql;

            // Prepare statement
            $sth = $this->handle->prepare($sql);

            // Failed to execute statement, stop here
            if (FALSE === $sth->execute($binds)) {
                return FALSE;
            }

            // Retrieve statement result
            $row = $sth->fetch(PDO::FETCH_ASSOC);

            // Statement yielded no results, stop here
            if ( ! $row) {
                return FALSE;
            }

            // (array) Decrypt fields if needed
            return $this->crypt['auto'] ? $this->decryptFields($row) : $row;
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Fetch multiple result sets (rows).
     * ---
     * @param   string      SQL query.
     * @param   variadic    [optional] Query binds. Can be arrays, arguments, or both.
     * @return  bool|array  FALSE if no results found, array otherwise.
     */
    public function fetchAll($sql, ...$args)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Get query bindings
            $binds = $this->prepBinds($args, 0);

            // Save query
            $this->lastQuery = $sql;

            // Prepare statement
            $sth = $this->handle->prepare($sql);

            // Failed to execute statement
            if (FALSE === $sth->execute($binds)) {
                return FALSE;
            }

            // Fetch all results
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            // No results found, return FALSE rather than empty array
            if (empty($result)) {
                return FALSE;
            }

            // (array) Decrypt rows if needed
            return $this->crypt['auto'] ? $this->decryptRows($result) : $result;
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Fetch multiple result sets (i.e. rows), and format as a one-dimensional array
     * (list) using a formatting string array as the second argument. Useful for querying
     * form <option> lists.
     * ---
     * ) // Query sharks & rays
     * ) $sql = "SELECT id, genus, species, common_name, "
     * )      . "       CONCAT(genus, ' ', species) AS scientific_name "
     * )      . "FROM animals "
     * )      . "WHERE subclass = 'Elasmobranchii' "
     * )      . "LIMIT 3";
     * )
     * ) // Execute query
     * ) $common     = $db->fetchList($sql, 'common_name');
     * ) $scientific = $db->fetchList($sql, ['scientific_name' => 'common_name']);
     * )
     * ) // Common names (integer keys)
     * ) $common = [
     * )     0 => 'African angelshark',
     * )     1 => 'African lanternshark',
     * )     2 => 'African ribbontail catshark',
     * ) ];
     * )
     * ) // Scientific names (scientific_name => common_name)
     * ) $scientific = [
     * )     'Squatina africana' => 'African angelshark',
     * )     'Etmopterus polli'  => 'African lanternshark',
     * )     'Eridacnis sinuans' => 'African ribbontail catshark',
     * ) ];
     * ---
     * @param   string      SQL query.
     * @param   array       Format as (example): ['field1' => 'field2']
     * @param   variadic    [optional] Query binds. Can be arrays, arguments, or both.
     * @return  bool|array  FALSE if no results found, array otherwise.
     */
    public function fetchList($sql, $format, ...$args)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Verify method is able to work with the requested format
            if ( ! is_string($format) && (is_array($format) && count($format) != 1)) {
                throw new InvalidArgumentException('Argument 2 must be a string "field1" '
                        . 'or a one-key array formatted as: field1 => field2');
            }

            // Get field1 => field2 names, as applicable
            $key = is_string($format) ? $format : current(array_keys($format));
            $val = is_string($format) ? FALSE   : current(array_values($format));

            // Get query bindings
            $binds = $this->prepBinds($args, 0);

            // Save query
            $this->lastQuery = $sql;

            // Prepare statement
            $sth = $this->handle->prepare($sql);

            // Failed to execute statement
            if (FALSE === $sth->execute($binds)) {
                return FALSE;
            // Start result set
            } else {
                $results = [];
            }

            // Loop through all query results
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                // Compile simple array list
                if (FALSE === $val && isset($row[$key])) {
                    $results[] = $row[$key];
                // Compile field1 => field2 list
                } elseif (isset($row[$key]) && isset($row[$val])) {
                    $results[$row[$key]] = $row[$val];
                // Invalid formatting
                } else {
                    throw new VineBadArrayException('Fields specified in argument 2 were '
                            . 'not queried in argument 1.');
                }
            }

            // No results found, return FALSE rather than empty array
            if (empty($results)) {
                return FALSE;
            }

            // (array) Decrypt fields if needed
            return $this->crypt['auto'] ? $this->decryptFields($results) : $results;
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Invalid formatting argument
        } catch (VineDataException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Get the number of rows affected by the last DELETE, INSERT, or UPDATE query.
     * ---
     * @return  int
     */
    public function getAffectedRows()
    {
        return $this->affectedRows;
    }

    /**
     * Get the auto increment ID generated from the last INSERT query.
     * ---
     * @return  int
     */
    public function getInsertId()
    {
        return $this->insertId;
    }

    /**
     * Get the next auto-increment ID for a specified table. This is the only fast and
     * accurate method of getting the correct ID. Do NOT query MAX(id) as it may not
     * yield accurate results (if certain "end rows" have been deleted, for example).
     * ---
     * @param   string  The table to query.
     * @return  int     The next auto-increment ID.
     */
    public function getNextId($table)
    {
        $id = $this->fetch("SHOW TABLE STATUS LIKE '" . $table . "'");
        return $id['Auto_increment'];
    }

    /**
     * Get the last query generated using this wrapper. Queries made when developer
     * accesses the PDO handle directly will not be recorded, and so can't be retrieved
     * using this method.
     * ---
     * @return  string
     */
    public function getLastQuery()
    {
        return $this->lastQuery;
    }

    /**
     * Insert a row into a database table.
     * ---
     * @param   string    Table name.
     * @param   array     Update data. Format: field => value.
     * @return  bool|int  FALSE if insert failed, insert ID otherwise.
     */
    public function insert($table, $data)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Verify table name
            if ( ! is_string($table)) {
                throw new InvalidArgumentException('Argument 1 must be a string.');
            }

            // Verify insert data
            if ( ! is_array($data) || empty($data)) {
                throw new InvalidArgumentException('Argument 2 must be a valid field => '
                        . 'value array.');
            }

            // Compile insert query
            $sql = 'INSERT INTO `' . $table . '` '
                 . '(`' . implode('`, `', array_keys($data)) . '`) '
                 . 'VALUES(' . $this->prepMarkers($data, FALSE) . ')';

            // Save query
            $this->lastQuery = $sql;

            // Prepare and execute statement
            $sth    = $this->handle->prepare($sql);
            $result = $sth->execute(array_values($data));

            // Query failed
            if (FALSE === $result) {
                $this->affectedRows = 0;
                return FALSE;
            // Query successful, return the insert ID
            } else {
                $this->insertId = $this->handle->lastInsertId();
                return $this->insertId;
            }
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Execute a MySQL query.
     * ---
     * @param   string    The MySQL query to execute.
     * @param   variadic  [optional] Query binds. Can be arrays, arguments, or both.
     * @return  bool      TRUE if query successfully executed, FALSE otherwise.
     */
    public function query($sql, ...$args)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Get query bindings
            $binds = $this->prepBinds($args, 0);

            // The first argument is supposed to be the query
            if ( ! is_string($sql)) {
                throw new InvalidArgumentException('Argument 1 must be a SQL string.');
            }

            // Save query
            $this->lastQuery = $sql;

            // Prepare and execute statement
            $sth    = $this->handle->prepare($sql);
            $result = $sth->execute($binds);

            // Query failed
            if (FALSE === $result) {
                $this->affectedRows = 0;
                return FALSE;
            // Query successful
            } else {
                $this->affectedRows = $sth->rowCount();
                return TRUE;
            }
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e); exit(1);
        }
    }

    /**
     * Update row(s) in a database table.
     * ---
     * @param   string    Table name.
     * @param   array     Update data. Format: field => value.
     * @param   string    Where statement. Example: id = ?
     * @param   int       Update limit.
     * @param   variadic  [optional] Query binds. Can be arrays, arguments, or both.
     * @return  bool
     */
    public function update($table, $data, $where = NULL, $limit = 1, ...$args)
    {
        try {
            // Connect to database if necessary
            $this->connect();

            // Verify table name
            if ( ! is_string($table)) {
                throw new InvalidArgumentException('Argument 1 must be a string.');
            }

            // Verify update data
            if ( ! is_array($data) || empty($data)) {
                throw new InvalidArgumentException('Argument 2 must be a valid field => '
                        . 'value array.');
            }

            // Get query bindings
            $binds = array_merge(array_values($data), $this->prepBinds($args, 0));

            // Compile query
            $sql = 'UPDATE `' . $table . '` SET ' . $this->prepMarkers($data, TRUE)
                 . (is_string($where) ? ' WHERE ' . $where : '')
                 . ($limit > 0 ? ' LIMIT ' . (int) $limit : '');

            // Save query
            $this->lastQuery = $sql;

            // Prepare and execute statement
            $sth    = $this->handle->prepare($sql);
            $result = $sth->execute($binds);

            // Query failed
            if (FALSE === $result) {
                $this->affectedRows = 0;
                return FALSE;
            // Query successful
            } else {
                $this->affectedRows = $sth->rowCount();
                return TRUE;
            }
        // Invalid query
        } catch (PDOException $e) {
            Vine_Exception::handle($e);
        // Fatal exception
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
            exit(1);
        }
    }

    /**
     * @see  self::toBinds()
     */
    protected function prepBinds($args, $start = 1)
    {
        return self::toBinds($args, $start);
    }

    /**
     * Prepare DSN for PDO.
     * ---
     * @param   array
     * @return  string
     */
    protected function prepDsn($config)
    {
        return $config['type']
            . ':dbname=' . $config['database']
            . ';host=' . $config['host'];
    }

    /**
     * Prepare query markers for an insert() or update() query.
     * ---
     * @param   array
     * @param   bool
     * @return  string
     */
    protected function prepMarkers($data, $update = FALSE)
    {
        // Only the names of the fields are needed
        $fields = array_keys($data);

        // Loop though each field
        foreach ($fields as $key => $field) {
            // Markers for an insert query
            if (FALSE === $update) {
                $fields[$key] = '?';
            // Markers for an update query
            } else {
                $fields[$key] = '`' . $field . '` = ?';
            }
        }

        // Return result as comma delimited string
        return implode(', ', $fields);
    }

    /**
     * Decrypt a set of database fields (i.e. a database row).
     * ---
     * @param   array       The fields to decrypt.
     * @return  bool|array  FALSE if fields invalid, array otherwise.
     */
    protected function decryptFields($fields)
    {
        // Invalid fieldset
        if ( ! is_array($fields) || empty($fields)) {
            return FALSE;
        }

        // Loop through and decrypt each field as needed
        foreach ($fields as $i => $v) {
            if (Vine_Security::isEncrypted($v)) {
                $fields[$i] = Vine_Security::decrypt($v);
            }
        }

        // (array) Decrypted fields
        return $fields;
    }

    /**
     * Decrypt a set of database rows.
     * ---
     * @param   array       The database rows to decrypt.
     * @return  bool|array  FALSE if rows invalid, array otherwise.
     */
    protected function decryptRows($rows)
    {
        // Invalid rows
        if ( ! is_array($rows) || empty($rows)) {
            return FALSE;
        }

        // Loop through and decrypt each row as needed
        foreach ($rows as $i => $v) {
            $rows[$i] = $this->decryptFields($v);
        }

        // (array) Decrypted rows
        return $rows;
    }
}
