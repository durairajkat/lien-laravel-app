<?php

/**
 * FTP Handle Wrapper.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Ftp
{
    /**
     * FTP connection.
     * ---
     * @var  stream
     */
    protected $handle = NULL;

    /**
     * Error message.
     * ---
     * @var  string
     */
    protected $error = NULL;

    /**
     * Default FTP configuration.
     * ---
     * @var  array
     */
    protected $config = [
        'hostname'     => NULL,
        'username'     => NULL,
        'password'     => NULL,
        'port'         => 21,
        'timeout'      => 90,
        'passive-mode' => FALSE,
        'default-path' => '',
    ];

    /**
     * Class constructor.
     * ---
     * @param   array  FTP connection and configuration array.
     * @return  void
     */
    public function __construct(array $config)
    {
        // Ensure config array has all necessary keys (to avoid E_NOTICE errors)
        $config = $this->doStandardize(array_merge($this->config, $config));

        // Valid configuration, attempt to connect
        if ($this->doVerify($config)) {
            $this->connect($config);
        }
    }

    /**
     * Class destructor. Close FTP connection (if any).
     * ---
     * @return  void
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * Connect and login to FTP server.
     * ---
     * @param   array  FTP connection and configuration array.
     * @return  bool   TRUE if connection successful, FALSE otherwise.
     */
    public function connect(array $config)
    {
        // Connect to FTP server
        $this->handle = @ftp_connect(
            $config['hostname'],
            $config['port'],
            $config['timeout']
        );

        // Connection failed, stop here
        if (FALSE === $this->handle) {
            $this->setError('Failed to connect to FTP server.');
            return FALSE;
        }

        // Successfully connected but invalid login credentials, stop here
        if (FALSE === @ftp_login(
            $this->handle,
            $config['username'],
            $config['password'])
        ) {
            $this->setError('Failed to login to FTP server.');
            return FALSE;
        }

        // Failed to enable passive mode, stop here
        if (    TRUE === $config['passive-mode']
            && FALSE === @ftp_pasv($this->handle, TRUE)
        ) {
            $this->setError('Failed to enable FTP passive mode.');
            return FALSE;
        }

        // Navigate to default directory
        if ($config['default-path']) {
            ftp_chdir($this->handle, $config['default-path']);
        }

        // Successfully connected
        return TRUE;
    }

    /**
     * Disconnect from FTP server (closes link ID and resource).
     * ---
     * @return  void
     */
    public function disconnect()
    {
        if ($this->handle) {
            @ftp_close($this->handle);
        }
    }

    /**
     * Set an error message.
     * ---
     * @param   string  Error message.
     * @return  void
     */
    public function setError($error)
    {
        // More than one error, grab first error
        if (is_array($error) && ! empty($error)) {
            $error = array_values($error);
            $error = $error[0];
        }

        // Set error
        if (strlen($error) && NULL === $this->error) {
            $this->error = $error;
        }
    }

    /**
     * Get an error message.
     * ---
     * @return  NULL if no error, string otherwise.
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * See if script has encountered any errors.
     * ---
     * @return  bool  TRUE if no errors found, FALSE otherwise.
     */
    public function isValid()
    {
        return NULL === $this->error;
    }

    /**
     * See if currently connected to FTP server.
     * ---
     * @return  bool
     */
    public function isConnected()
    {
        return $this->handle ? TRUE : FALSE;
    }

    /**
     * Get an array listing all files and directories in a specified directory path.
     * ---
     * @param   string      The base directory to look in.
     * @param   bool        [optional] Get the basename of all items? FALSE = default.
     * @return  array|bool  FALSE if no subdirectories or files found, array otherwise.
     */
    public function getAll($ftpPath = '', $baseName = FALSE)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Get all items
        $items = @ftp_nlist($this->handle, $ftpPath);

        // Get basename(s) of all results
        if ($baseName) {
            $items = $this->getBasenames($items);
        }

        // Return result
        return is_array($items) && ! empty($items) ? $items : FALSE;
    }

    /**
     * Get an array listing all subdirectories in a specified directory path.
     * ---
     * @param   string      The base directory to look in.
     * @param   bool        [optional] Get the basename of each directory? FALSE = default.
     * @return  array|bool  FALSE if no subdirectories found, array otherwise.
     */
    public function getDirectories($ftpPath = '', $baseName = FALSE)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Get all items
        $items = ftp_nlist($this->handle, $ftpPath);

        // Remove files from result list
        if (is_array($items) && ! empty($items)) {
            // Loop through all results and only keep directories
            foreach ($items as $k => $v) {
                // This FTP user isn't returning the full file paths, adjust accordingly
                if ($this->isDirectory($ftpPath . '/' . $v)) {
                    $items[$k] = $ftpPath . '/' . $v;
                // This is a file, remove from list
                } elseif ( ! $this->isDirectory($v)) {
                    unset($items[$k]);
                }

                // This is a navigation path, ignore
                if ('.' === basename($v) || '..' === basename($v)) {
                    unset($items[$k]);
                }
            }
        }

        // Get basename(s) of all results
        if ($baseName) {
            $items = $this->getBasenames($items);
        }

        // Return result
        return is_array($items) && ! empty($items) ? $items : FALSE;
    }

    /**
     * Get an array listing all files in a specified directory path.
     * ---
     * @param   string      The base directory to search under.
     * @param   bool        [optional] Get the basename of each file? FALSE = default.
     * @return  array|bool  FALSE if no files found, array otherwise.
     */
    public function getFiles($ftpPath = '', $baseName = FALSE)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Get all items
        $items = @ftp_nlist($this->handle, $ftpPath);

        // Remove directories from result list
        if (is_array($items) && ! empty($items)) {
            // Loop through all results and only keep files
            foreach ($items as $k => $v) {
                // This is directory, remove from list
                if ($this->isDirectory($v, $ftpPath)) {
                    unset($items[$k]);
                }
            }
        }

        // Get basename(s) of all results
        if ($baseName) {
            $items = $this->getBasenames($items);
        }

        // Return result
        return is_array($items) && ! empty($items) ? $items : FALSE;
    }

    /**
     * [!!!] Recursive. Delete a specified FTP directory.
     * ---
     * @param   string  The path of the directory to delete.
     * @param   bool    [optional] If FALSE, must be empty. If TRUE, delete all sub-files.
     * @return  bool    TRUE if directory deleted successfully, FALSE otherwise.
     */
    public function deleteDirectory($ftpPath, $force = FALSE)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // For delete directory even if it's not empty
        if ($force) {
            // That didn't work, so now get all items in this directory
            $items = $this->getAll($ftpPath, FALSE);

            // This directory is not empty, recursively delete
            if (is_array($items) && ! empty($items)) {
                // Loop through and delete each item
                foreach ($items as $v) {
                    // This is a directory navigator, ignore
                    if ('.' === $v || '..' === $v) {
                        continue;
                    }

                    // Delete a directory, goto next
                    if ($this->isDirectory($v) &&  $this->deleteDirectory($v, TRUE)) {
                        continue;
                    }

                    // Delete a file, goto next
                    if ($this->deleteFile($v)) {
                        continue;
                    }

                    // Something went wrong, try again by prepending path to file name
                    $v = $ftpPath . '/' . $v;

                    // Delete a directory, goto next
                    if ($this->isDirectory($v) &&  $this->deleteDirectory($v, TRUE)) {
                        continue;
                    }

                    // Delete a file, goto next
                    if ($this->deleteFile($v)) {
                        continue;
                    }
                }
            }
        }

        // If this fails that means FTP user lacks permission to delete
        if ( ! @ftp_rmdir($this->handle, $ftpPath)) {
            return FALSE;
        }

        // Everything successful (this is a loose check; need to improve)
        return TRUE;
    }

    /**
     * Delete a specified FTP file.
     * ---
     * @param  string  The path to the FTP file.
     */
    public function deleteFile($ftpPath)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // File successfully deleted
        if (@ftp_delete($this->handle, $ftpPath)) {
            return TRUE;
        // Failed to delete file
        } else {
            return FALSE;
        }
    }

    /**
     * Download all of the contents of an FTP directory to a specified local directory.
     * ---
     * @param   string  The path of the FTP directory to download files from.
     * @param   string  The path of the local directory to download contents to.
     * @return  bool    TRUE if directory downloaded successfully, FALSE otherwise.
     */
    public function downloadDirectory($ftpPath, $localPath = FALSE)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Start in specified local directory
        if (FALSE !== $localPath) {
            @chdir($localPath);
        }

        // Create and navigate to this directory
        if ('.' !== $ftpPath) {
            // Can't access FTP directory, probably lack permissions, that's OK
            if (FALSE === @ftp_chdir($this->handle, $ftpPath)) {
                return TRUE;
            }

            // Create this directory locally
            if ( ! (@is_dir($localPath . $ftpPath))) {
                @mkdir($localPath . $ftpPath);
            }

            // Navigate to this directory locally
            @chdir($ftpPath);
        }

        // Get all of the items in this directory
        $items = @ftp_nlist($this->handle, '.');

        // Loop through all items in this directory
        foreach ($items as $file) {
            // This is a directory navigator, ignore
            if ('.' === $file || '..' === $file) {
                continue;
            }

            // (Recursive) This is an FTP directory, navigate to and mirror it
            if (@ftp_chdir($this->handle, $file)) {
                @ftp_chdir($this->handle, '..');
                $this->downloadDirectory($file, FALSE);
            // This is a file, download it now
            }  else {
                @ftp_get($this->handle, $file, $file, FTP_BINARY);
            }
        }

        // All the files in this directory have been downloaded, navigate back up tree
        @ftp_chdir($this->handle, '..');
        @chdir('..');

        // It's really hard to error check this, so assume successful
        return TRUE;
    }

    /**
     * Download a specified FTP file to a specified local file path.
     * ---
     * @param   string  The path of the FTP file to download.
     * @param   string  The path of the local file to save to. Replaced if already exists.
     * @return  bool    TRUE if file downloaded successfully, FALSE otherwise.
     */
    public function downloadFile($ftpPath, $localPath)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Successfully downloaded file to specified local path
        if (@ftp_get($this->handle, $localPath, $ftpPath, FTP_BINARY)) {
            return TRUE;
        // Failed to download file
        } else {
            return FALSE;
        }
    }

    /**
     * [!!!] Recursive. Upload all of the contents of local to specified FTP directory.
     * ---
     * @param   string  The path of the local directory to upload contents from.
     * @param   string  The path of the FTP directory to upload contents to.
     * @return  bool    TRUE if all contents uploaded successfully, FALSE otherwise.
     */
    public function uploadDirectory($localPath, $ftpPath)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // The current directory
        $d = dir($localPath);

        // Loop through all contents of local directory
        while ($f = $d->read()) {
            // Valid file or directory, try uploading
            if ($f !== '.' && $f !== '..') {
                // This is a directory, recursively mirror it on FTP server
                if (is_dir($localPath . '/' . $f)) {
                    // Directory doesn't exist on FTP yet, create it now
                    if ( ! @ftp_chdir($this->handle, $ftpPath . '/' . $f)) {
                        @ftp_mkdir($this->handle, $ftpPath . '/' . $f);
                    }

                    // (recursion)
                    $this->uploadDirectory($localPath . '/'. $f, $ftpPath . '/' . $f);
                // This is a file, upload it now
                } else {
                    $this->uploadFile($localPath . '/' . $f, $ftpPath . '/' . $f);
                }
            }
        }

        // Close local directory
        $d->close();

        // It's really hard to error check this, so assume successful
        return TRUE;
    }

    /**
     * Upload a local file a specified FTP file path.
     * ---
     * @param   string  The path of the local file to upload.
     * @param   string  The path of the FTP file to create or replace.
     * @return  bool    TRUE if file uploaded successfully, FALSE otherwise.
     */
    public function uploadFile($localPath, $ftpPath)
    {
        // Immediately stop if an error has already occurred or if not connected
        if ( ! $this->isValid() && $this->isConnected()) {
            return FALSE;
        }

        // Successfully uploaded
        if (@ftp_put($this->handle, $ftpPath, $localPath, FTP_BINARY)) {
            return TRUE;
        // Failed to upload
        } else {
            return FALSE;
        }
    }

    /**
     * Standardize FTP configuration.
     * ---
     * @param   array  FTP connection and configuration array.
     * @return  array  Standardized FTP connection and configuration array.
     */
    protected function doStandardize(array $config)
    {
        $config['passive-mode'] = Vine_Quick::toBool($config['passive-mode']);
        $config['default-path'] = rtrim(rtrim($config['default-path'], '\/'), '\/');
        $config['port']         = (int) $config['port'];
        $config['timeout']      = (int) $config['timeout'];
        return $config;
    }

    /**
     * Verify the FTP configuration before connecting.
     * ---
     * @param   array  FTP connection and configuration array.
     * @return  bool   TRUE if FTP configuration is acceptable, FALSE otherwise.
     */
    protected function doVerify(array $config)
    {
        // Verify that a hostname has been provided
        if ( ! Vine_Verify::length($config['hostname'], 2)) {
            $this->setError('FTP "hostname" invalid.');
            return FALSE;
        }

        // Verify that a username has been provided
        if ( ! Vine_Verify::length($config['username'], 2)) {
            $this->setError('FTP "username" invalid.');
            return FALSE;
        }

        // Verify that a password has been provided (never "bake-in" public FTP support)
        if ( ! Vine_Verify::length($config['password'], 2)) {
            $this->setError('FTP "password" invalid.');
            return FALSE;
        }

        // Verify that a port has been provided
        if ( ! Vine_Verify::length($config['port'], 1)) {
            $this->setError('FTP "port" invalid.');
            return FALSE;
        }

        // Everything is valid
        return TRUE;
    }

    /**
     * [!!!] Recursive. Get the basenames of a file and/or directory list.
     * ---
     * @param   mixed
     * @return  mixed
     */
    protected function getBasenames($items)
    {
        // Recursively get basenames of all items in an array
        if (is_array($items) && ! empty($items)) {
            // Loop through and get basenames for each item
            foreach ($items as $k => $v) {
                $items[$k] = $this->getBasenames($v);
            }
        // Get the basename of a single file or directory
        } else if (is_string($items) || is_int($items) || is_float($items)) {
            $items = basename($items);
        }

        // (mixed) Basename'd results
        return $items;
    }

    /**
     * See if a specified path is a directory.
     * ---
     * @param   string
     * @return  TRUE if path is a directory, FALSE otherwise.
     */
    protected function isDirectory($path)
    {
        // The current directory
        $from = @ftp_pwd($this->handle);

        // A valid directory if you can navigate to it
        if (@ftp_chdir($this->handle, $path)) {
            @ftp_chdir($this->handle, $from);
            return TRUE;
        // Not a valid directory if ftp_chdir fails
        } else {
            return FALSE;
        }
    }
}
