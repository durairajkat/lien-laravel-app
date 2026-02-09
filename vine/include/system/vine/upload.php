<?php

/**
 * File Uploads
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Upload
{
    /**
     * For automatically generated error messages.
     */
    const ERROR_TAG_FIELD     = '%field%';
    const ERROR_TAG_FILE      = '%file%';
    const ERROR_FILE_REQUIRED = '%field% is a required field.';
    const ERROR_FILE_TYPE     = '%file% - file type is not supported.';
    const ERROR_FILE_NAME     = '%file% - file name is too long.';
    const ERROR_FILE_SIZE     = '%file% - file size is too large.';
    const ERROR_MAX_WIDTH     = '%file% - max image width exceeded.';
    const ERROR_MAX_HEIGHT    = '%file% - max image height exceeded.';

    /**
     * Default file upload configuration.
     * ---
     * @var  array
     */
    protected $config = [
        'test-mode' => FALSE,
        'test-ip'   => NULL,
    ];

    /**
     * Default rules for uploaded files.
     * ---
     * @var  array
     */
    protected $rules = [
        'required'   => TRUE,
        'max-size'   => 0,
        'max-length' => 0,
        'max-width'  => 0,
        'max-height' => 0,
        'extensions' => [],
    ];

    /**
     * Valid image types to check pixel size for (if applicable). All of these image
     * types are supported natively in PHP >= 4.3.2.
     * ---
     * @var array
     */
    protected $images = [
        'gif', 'jpg', 'jpeg', 'png', 'bmp', 'tiff',
        'jpc', 'jp2', 'jpx', 'jb2', 'iff', 'wbmp', 'xbm',
    ];

    /**
     * Workspace. Fixed $_FILES and upload errors.
     * ---
     * @var  array
     */
    protected $files   = [];
    protected $errors  = [];

    /**
     * Path to cache directory.
     * ---
     * @var  string
     */
    private $_cachePath = NULL;

    /**
     * Cached file uploads.
     * ---
     * @var  array
     */
    private $_cacheFiles = [];

    /**
     * Has debug() been ran?
     * ---
     * @var  bool
     */
    private $_debugged = FALSE;

    /**
     * Class constructor.
     * ---
     * ) Load upload configuration settings.
     * ) Set cache path (loaded from registry if not provided).
     * ) Fix and sanitize the $_FILES superglobal.
     * ---
     * @param   array
     * @param   string
     * @return  void
     */
    public function __construct($config = NULL, $cachePath = NULL)
    {
        try {
            // Load cache path from registry if not provided
            if (NULL === $cachePath) {
                $cachePath = Vine_Registry::getSetting(Vine::CACHE_PATH);
            }

            // Standardize cache path
            $this->_cachePath = realpath($cachePath) . '/';

            // Unable to work with this cache path
            if ( ! is_dir($this->_cachePath) || ! is_writable($this->_cachePath)) {
                throw new VinePermissionsException('Unable to read and/or write to cache '
                        . 'directory: ' . $this->_cachePath);
            }

            // Load config
            if (is_array($config) && ! empty($config)) {
                $this->config = array_merge($this->config, $config);
            }

            // Re-map $_FILES superglobal
            $this->files = $this->fixFiles($_FILES);
        // Fatal exception
        } catch (VinePermissionsException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Class destructor.
     * ---
     * ) Clear uploaded file cache.
     * ) Run debug() if applicable.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Save upload data to debugging log, and email results if applicable
        if (   $this->config['test-mode']
            || $_SERVER['REMOTE_ADDR'] == $this->config['test-ip']
        ) {
            $this->debug();
        }

        // No files need cleared from cache
        if (empty($this->_cacheFiles)) {
            return;
        }

        // Clear all the status cache for the files
        clearstatcache();

        // Loop through each uploaded file, and if it still exists, delete it
        foreach ($this->_cacheFiles as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * For test/production environment unit testing. Silently debug file uploader.
     * ---
     * ) Dump re-mapped $_FILES.
     * ) Dump errors.
     * ) Log results.
     * ) Email results if applicable.
     * ---
     * @return  void
     */
    public function debug()
    {
        // Only debug if file upload attempted, and only debug once per request
        if (empty($this->files) || TRUE === $this->_debugged) {
            return;
        }

        // Only debug once per request
        $this->_debugged = TRUE;

        // Prepare data
        $files  = print_r($this->files, TRUE);
        $errors = print_r($this->errors, TRUE);

        // Compile debugging result
        $msg  = "Files:\n\n";
        $msg .= $files . "\n";
        $msg .= "--\n";
        $msg .= "Errors:\n\n";
        $msg .= $errors;

        // Log debugging result
        Vine_Log::logDebug($msg);
    }

    /**
     * Get a file upload field's info array, or get a specific key from the info array of
     * a file upload field.
     * ---
     * @param   string      [optional] When NULL, the entire $files property is returned.
     * @return  array|bool  FALSE if file upload field not found, array otherwise,
     */
    public function get($field = NULL)
    {
        // Return all of the upload data
        if (NULL === $field) {
            return $this->files;
        }

        // Return requested field
        return Vine_Array::getKey($this->files, $field);
    }

    /**
     * Get all of the file upload errors generated after applying the setRules() method to
     * specified upload fields.
     * ---
     * @return  array  Will be empty if no errors were found.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * See if all file uploads are valid after all rules have been set and applied.
     * ---
     * [!!!] This method should be used AFTER setting all of the rules for each upload
     * field with the setRules() method.
     * ---
     * @return bool
     */
    public function isValid()
    {
        return empty($this->errors);
    }

    /**
     * Set a rule for a specified file upload, or set of file uploads.
     * ---
     * @param   string  The file upload(s) to verify.
     * @param   string  The readable name of the field. Used for error generation.
     * @param   array   The rules configuration for upload(s).
     * @return  void
     */
    public function setRules($field, $description, array $rules)
    {
        // Get file info
        $data = Vine_Array::getKey($this->files, $field);

        // Extend off of default rules
        $rules = array_merge($this->rules, $rules);

        // (recursion) Rules apply to multiple files, use _setMultiRules()
        if ( ! $this->_isUploadArray($data) && is_array($data)) {
            $this->_setMultiRules($field, $description, $data, $rules);
        // Apply rules
        } elseif (is_array($data)) {
            $this->_verifyFile($description, $data, $rules);
        // File not uploaded, but is required
        } elseif (TRUE === filter_var($rules['required'], FILTER_VALIDATE_BOOLEAN)) {
            $this->setError($description, NULL, self::ERROR_FILE_REQUIRED);
        }
    }

    /**
     * PHP's $_FILES superglobal is inconsistent when compared to the $_POST and $_GET
     * superglobals. The inconsistency starts when files are uploaded from fields that
     * have square bracket naming, such as the example below.
     * ---
     * ) <!-- Will appear in $_FILES superglobal as expected -->
     * ) <input type="file" name="attachment1" />
     * ) <input type="file" name="attachment2" />
     * )
     * ) <!-- Will NOT appear in $_FILES superglobal as expected -->
     * ) <input type="file" name="attachments[]" />
     * ) <input type="file" name="attachments[]" />
     * ---
     * This method will re-map the $_FILES superglobal, and return a "fixed" array, which
     * is consistent with the $_POST and $_GET superglobals.
     * ---
     * [!!!] This method NOT alter the actual $_FILES superglobal.
     * ---
     * @param   array  Broken $_FILES superglobal.
     * @return  array  Fixed $_FILES superglobal.
     */
    protected function fixFiles(array $files)
    {
        // Fixed $_FILES workspace
        $new = [];

        // No $_FILES
        if (empty($files)) {
            return $new;
        }

        // Loop through $_FILES superglobal and reconstruct it
        foreach ($files as $index => $info) {
            // Silently ignore invalid datasets
            if ( ! $this->_isUploadArray($info)) {
                continue;
            }

            // Complex file upload, contains sub-files
            if (is_array($info['name'])) {
                // Build a file dataset, and start possible recursion
                $this->_fixFile(
                    $new[$index],
                    $info['name'],
                    $info['type'],
                    $info['tmp_name'],
                    $info['error'],
                    $info['size']
                );

            // Simple file upload
            } else {
                // Move file to cache directory
                $this->_moveToCache($info);

                // $info was updated in _moveToCache() - passed by reference
                $new[$index] = $info;
            }
        }

        // (array) Fixed $_FILES
        return $new;
    }

    /**
     * Make and save an error message.
     * ---
     * @param   string
     * @param   string
     * @param   string
     * @return  void
     */
    protected function setError($field, $file, $error)
    {
        $error = str_replace(self::ERROR_TAG_FIELD, $field, $error);
        $error = str_replace(self::ERROR_TAG_FILE, $file, $error);
        $this->errors[] = $error;
    }

    /**
     * Recursive support method for fixFiles().
     * ---
     * @param   array
     * @param   string|array
     * @param   string|array
     * @param   string|array
     * @param   string|array
     * @param   int|array
     * @return  void
     */
    private function _fixFile(&$new, $name, $type, $tmp, $error, $size)
    {
        // No more sub-items
        if ( ! is_array($name)) {
            // Compile file's applicable info
            $new['name']     = $name;
            $new['type']     = $type;
            $new['tmp_name'] = $tmp;
            $new['error']    = $error;
            $new['size']     = $size;

            // Move file to cache directory - passed by reference
            $this->_moveToCache($new);

            // Finished
            return;
        }

        // (recursion) Loop through each sub-item
        foreach ($name as $index => $info) {
            $this->_fixFile(
                $new[$index],
                $name[$index],
                $type[$index],
                $tmp[$index],
                $error[$index],
                $size[$index]
            );
        }
    }

    /**
     * Support method for fixFiles().
     * ---
     * @param   array
     * @return  bool
     */
    private function _isUploadArray($data)
    {
        // File array must have exactly 5 keys
        if ( ! is_array($data) || count($data) !== 5) {
            return FALSE;
        }

        // Get numeric keys of the original array
        $info = array_values(array_keys($data));

        // Check names of the keys, and check them in the exact order they should be in
        if (   $info[0] === 'name'
            && $info[1] === 'type'
            && $info[2] === 'tmp_name'
            && $info[3] === 'error'
            && $info[4] === 'size'
        ) {
            return TRUE;
        // This is not an upload array
        } else {
            return FALSE;
        }
    }

    /**
     * Support method for fixFiles() and _fixFile().
     * ---
     * @param   array  [reference] Passed by reference.
     * @return  void
     */
    private function _moveToCache(array &$info)
    {
        // Not an upload (i.e. empty file input field), stop here
        if ( ! is_uploaded_file($info['tmp_name'])) {
            return;
        }

        // Get file extension to append to tmp_name (useful for getting mime-type)
        $ext  = Vine_File::getExtension($info['name']);
        $file = basename($info['tmp_name']) . '.' . $ext;

        // Update $info array (passed by reference)
        $old = $info['tmp_name'];
        $new = $this->_cachePath . $file;

        // Move to cache directory
        if (copy($old, $new)) {
            // Update $info array (passed by reference)
            $info['tmp_name'] = $new;
            $info['type']     = Vine_File::getMimeType($new);

            // Save all cached files so they can be deleted later. @see __destruct()
            $this->_cacheFiles[] = $new;
        }
    }

    /**
     * Helper method for setRules(). Sets rules for multiple upload files.
     * ---
     * @param  string
     * @param  string
     * @param  array
     * @param  array
     */
    private function _setMultiRules($field, $description, array $info, array $rules)
    {
        // Not reached end file(s) info yet
        if ( ! $this->_isUploadArray($info) && is_array($info)) {
            // Loop through each sub-field
            foreach ($info as $data) {
                $this->_setMultiRules($field, $description, $data, $rules);
            }
        // Apply rules
        } elseif (is_array($info)) {
            $this->_verifyFile($field, $info, $rules);
        }
    }

    /**
     * Verify a file upload.
     * ---
     * @param   string
     * @param   array
     * @param   array
     * @return  void
     */
    private function _verifyFile($description, array $info, array $rules)
    {
        // Compile file info, settings, and rules
        $exists  = strlen($info['tmp_name']) ? is_file($info['tmp_name']) : FALSE;
        $isReq   = filter_var($rules['required'], FILTER_VALIDATE_BOOLEAN);
        $ext     = Vine_File::getExtension($info['name']);
        $exts    = array_change_key_case(array_flip($rules['extensions']), CASE_LOWER);
        $isImg   = in_array($ext, $this->images);
        $imgInfo = $isImg ? @getImageSize($info['tmp_name']) : FALSE;
        $isPost  = 'POST' === Vine_Request::getMethod();

        // File is required but doesn't exist, stop here
        if ($isPost && $isReq && ! $exists) {
            $this->setError($description, $info['name'], self::ERROR_FILE_REQUIRED);
            return;
        }

        // File is not required and doesn't exist, stop here
        if ( ! $exists) {
            return;
        }


        // Extension is not supported, stop here
        if ( ! empty($exts) && ! isset($exts[$ext])) {
            $this->setError($description, $info['name'], self::ERROR_FILE_TYPE);
            return;
        }

        // Filesize limit exceeded, stop here
        if ($rules['max-size'] > 0 && $info['size'] > $rules['max-size']) {
            $this->setError($description, $info['name'], self::ERROR_FILE_SIZE);
            return;
        }

        // File length, stop here
        if ($rules['max-length'] > 0 && strlen($info['name']) > $rules['max-length']) {
            $this->setError($description, $info['name'], self::ERROR_FILE_NAME);
            return;
        }

        // Image too wide, stop here
        if ($imgInfo && $rules['max-width'] > 0 && $imgInfo[0] > $rules['max-width']) {
            $this->setError($description, $info['name'], self::ERROR_MAX_WIDTH);
            return;
        }

        // Image too tall, stop here
        if ($imgInfo && $rules['max-height'] > 0 && $imgInfo[1] > $rules['max-height']) {
            $this->setError($description, $info['name'], self::ERROR_MAX_HEIGHT);
            return;
        }
    }
}
