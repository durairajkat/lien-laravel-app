<?php

/**
 * Files & Directories
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_File
{
    /**
     * Mime-type to use when mime can't be found.
     */
    const DEFAULT_MIME = 'application/octet-stream';

    /**
     * Dubious (questionable) mime types. If getMimeType() returns one of these values,
     * the mime type will be pulled directly from the custom mime array using the file's
     * extension.
     * ---
     * @var  array
     */
    protected static $dubiousTypes = [
        'application/octet-stream',
        'text/plain',
    ];

    /**
     * Get an array listing all files in a specified directory path.
     * ---
     * @param   string      The base directory to look in.
     * @param   bool        (opt) Do a deep search inside all subdirectories?
     * @return  array|bool  FALSE if no files found, array otherwise.
     */
    public static function getFiles($base, $deep = FALSE)
    {
        // Get absolute path to specified directory
        $base  = self::strToDir(realpath($base));
        $files = [];

        // Directory does not exist
        if ( ! is_dir($base)) {
            return FALSE;
        }

        // Return only level 1 files, stop here
        if ( ! $deep) {
            // Get files
            $files = glob(realpath($base) . '/*');

            // No files found, stop here
            if (empty($files) || ! $files) {
                return FALSE;
            }

            // Loop through each result and only keep files
            foreach ($files as $k => $file) {
                // This is not a file
                if ( ! is_file($file)) {
                    unset($files[$k]);
                }
            }

            // Reset keys and return file list
            return empty($files) ? FALSE : array_values($files);
        }

        // Search filesystem starting at the base path
        $i = new RecursiveIteratorIterator(
             new RecursiveDirectoryIterator($base), RecursiveIteratorIterator::SELF_FIRST
        );

        // Loop through each item in the filesystem search
        foreach ($i as $file) {
            if ( ! $file->isDir()) {
                $files[] = $file->getRealpath();
            }
        }

        // (array|bool)
        return empty($files) ? FALSE : $files;
    }

    /**
     * Get an array listing all subdirectories in a specified directory path.
     * ---
     * @param   string      The base directory to look in.
     * @param   bool        (opt) Do a deep search inside all subdirectories?
     * @param   bool        (opt) Only return the basename of each directory?
     * @return  array|bool  FALSE if no subdirectories found, array otherwise.
     */
    public static function getDirectories($base, $deep = FALSE, $baseName = FALSE)
    {
        // Get absolute path to specified directory
        $base = self::strToDir(realpath($base));
        $dirs = [];

        // Directory does not exist
        if ( ! is_dir($base)) {
            return FALSE;
        }

        // Return only level 1 subdirectories, stop here
        if ( ! $deep) {
            // Get all of the level 1 subdirectories
            $dirs = glob(realpath($base) . '/*' , GLOB_ONLYDIR);

            // Only get the basename of each directory
            if ($baseName && $dirs && ! empty($dirs)) {
                // Loop through and get basename of each directory
                foreach ($dirs as $k => $v) {
                    $dirs[$k] = basename($v);
                }
            }

            // (bool|array)
            return (empty($dirs) || ! $dirs) ? FALSE : $dirs;
        }

        // Search filesystem starting at the base path
        $i = new RecursiveIteratorIterator(
             new RecursiveDirectoryIterator($base), RecursiveIteratorIterator::SELF_FIRST
        );

        // Loop through each item in the filesystem search
        foreach ($i as $file) {
            if ($file->isDir()) {
                $dirs[] = $baseName ? basename($file->getRealpath()) : $file->getRealPath();
            }
        }

        // (array|bool)
        return empty($dirs) ? FALSE : $dirs;
    }

    /**
     * Convert a byte count to a readable file size, such as:
     * ---
     * ) 1 KB
     * ) 352 KB
     * ) 1.20 MB
     * ) 52.60 MB
     * ) 1.10 GB
     * ---
     * @param   int|float  Total file size (in bytes).
     * @return  string     Human-readable file size.
     */
    public static function bytesToSize($bytes)
    {
        $bytes = (float) $bytes;

        if ($bytes <= 1024) {
            return '1 KB';
        }

        $kb = ceil($bytes / 1024);
        $mb = round($kb / 1024, 2);
        $gb = round($mb / 1024, 2);

        if ($gb >= 1) {
            return $gb . ' GB';
        } elseif ($mb >= 1) {
            return $mb . ' MB';
        } else {
            return $kb . ' KB';
        }
    }

    /**
     * Delete a directory. If directory has files in it, files will be recursivley
     * deleted. Any files inside directory will get deleted.
     * ---
     * @param   string
     * @return  bool
     */
    public static function deleteDir($dir) {
        try {
            // Sanitize directory tail
            $dir = rtrim(realpath($dir), '/\\') . DIRECTORY_SEPARATOR;

            // This method is intended for deleting files
            if ( ! is_dir($dir)) {
                throw new VineMissingFileException($dir . ' is not a valid directory!');
            // Change directory's permissions so any files in it can be deleted
            } else {
                chmod($dir, 0777);
            }

            // Delete all files in directory
            foreach (glob($dir . '*', GLOB_MARK) as $file) {
                // (recursion) Delete a sub-directory
                if (is_dir($file)) {
                    self::deleteDir($file);
                // Delete a file
                } else {
                    chmod($file, 0777);
                    unlink($file);
                }
            }

            // Delete directory
            rmdir($dir);
        } catch (VineMissingFileException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Get the extension of a file.
     * ---
     * @param   string
     * @return  string
     */
    public static function getExtension($file)
    {
        return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    /**
     * Get the mime-type for a file.
     * ---
     * @param   string
     * @return  string
     */
    public static function getMimeType($file)
    {
        // Start with an empty mime-type
        $type = '';

        // Use preferred method
        if (function_exists('finfo_file')) {
            $info = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($info, $file);
            finfo_close($info);
        // Use depreciated method
        } elseif (function_exists('mime_content_type')) {
            $type = mime_content_type($file);
        }

        // Mime-type likely couldn't be found, use file extension to get mime-type
        if ( ! $type || in_array($type, self::$dubiousTypes)) {
            // Get the extension of file and load mime-types list
            $ext   = self::getExtension($file);
            $types = require VINE_PATH . 'mime.php';

            // See if file extension is in list
            if (isset($types[$ext])) {
                $type = $types[$ext];
            // Extension was not in list
            } else {
                $type = self::DEFAULT_MIME;
            }
        }

        // (string) Result
        return $type;
    }

    /**
     * Convert a string to a directory path.
     * ---
     * @param   string
     * @return  string
     */
    public static function strToDir($str)
    {
        $str = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $str);
        $str = rtrim($str, '/\\') . DIRECTORY_SEPARATOR;
        return $str;
    }

    /**
     * Convert a string to a file name.
     * ---
     * @param   string
     * @return  string
     */
    public static function strToFile($str)
    {
        return preg_replace('/[^a-z0-9]+/', '-', strtolower($str));
    }
}
