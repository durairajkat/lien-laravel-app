<?php

/**
 * A helper class for getting, setting, and manipulating complex arrays.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Array
{
    /**
     * The value to return for a failed key search.
     */
    const RETURN_VALUE = FALSE;

    /**
     * jQuery style extend function. Merges two or more arrays. If argument 1 is TRUE
     * instead of an array, the rest of the array arguments will be recursively "deep"
     * extended.
     * ---
     * [!!!] If a non-array is passed into this method, it will be silently ignored.
     * ---
     * @params  array  Two or more arrays. If argument 1 is TRUE, function is recursive.
     * @return  array
     * @see     http://api.jquery.com/jQuery.extend
     */
    public static function extend()
    {
        // Arguments, argument count [faster than count($arg)], extended array
        $arg = func_get_args();
        $num = func_num_args();
        $ext = [];

        // Loop through each argument (faster than foreach)
        for ($i = 0; $i < $num; $i++) {
            // Deep extend
            if (TRUE === $arg[0]) {
                // Skip boolean argument (faster than else conditional)
                if ($i === 0) {
                    continue;
                }

                // Silently ignore non-arrays
                if (is_array($arg[$i]) && ! empty($arg[$i])) {
                    // Loop through each key in passed array
                    foreach ($arg[$i] as $key => $val) {
                        // Merge
                        if (is_string($key)) {
                            // Recursion
                            if (   is_array($val)
                                && isset($ext[$key])
                                && is_array($ext[$key])
                            ) {
                                $ext[$key] = self::extend(TRUE, $ext[$key], $val);

                            // left < right
                            } else {
                                $ext[$key] = $val;
                            }
                        // Append
                        } else {
                            $ext[] = $val;
                        }
                    }
                }
            // Shallow extend, silently ignore non-arrays
            } elseif (is_array($arg[$i])) {
                $ext = array_merge($ext, $arg[$i]);
            }
        }

        // Merged result
        return $ext;
    }

    /**
     * Retrieve an array key or value using a string accessor path.
     * ---
     * ) $arr = [
     * )     'attachments' => [
     * )         'images' => [
     * )             0 => 'whales.jpg',
     * )             1 => 'horses.jpg',
     * )             2 => 'flowers.jpg',
     * )             3 => 'dogs.jpg',
     * )         ],
     * )     ],
     * ) ];
     * )
     * ) // (string) flowers.jpg
     * ) $getFlowers = Vine_Array::getKey($arr, 'attachments[images][2]');
     * )
     * ) // (bool) FALSE
     * ) $getBadKey = Vine_Array::getKey($arr, 'attachments[images][4]');
     * ---
     * @param   string  The array to search in.
     * @param   string  The key to search for.
     * @return  mixed   FALSE if key not found or has a FALSE value, mixed otherwise.
     */
    public static function getKey(array $arr, $str)
    {
        // Sometimes developer may want a sub-array, indicating it by: foobar[]
        $str = str_replace('[]', '', $str);

        // Simple key name, and it exists
        if (array_key_exists($str, $arr)) {
            return $arr[$str];
        // Key does not exist, $str != array
        } elseif (FALSE === strpos($str, '[')) {
            return self::RETURN_VALUE;
        // Key does not exist, invalid $str
        } elseif (0 === preg_match_all('/\[(.*?)\]/', $str, $matches)) {
            return self::RETURN_VALUE;
        }

        // Key's basename
        $key = current(explode('[', $str));

        // Key's basename does not exist
        if ( ! array_key_exists($key, $arr)) {
            return self::RETURN_VALUE;
        // $key == array, basename key exists but is not an array
        } elseif ( ! is_array($arr[$key])) {
            return self::RETURN_VALUE;
        }

        // Get key's deep value, or FALSE if deep value doesn't exist
        return self::find($arr[$key], $matches[1]);
    }

    /**
     * Set a value in a multi-dimensional array, using a string setter path.
     * ---
     * ) $arr = [
     * )     'attachments' => [
     * )         'images' => [
     * )             0 => 'whales.jpg',
     * )             1 => 'horses.jpg',
     * )             2 => 'flowers.jpg',
     * )             3 => 'dogs.jpg',
     * )         ],
     * )     ],
     * ) ];
     * )
     * ) // Add 'dolphins.jpg' and 'cats.jpg'
     * ) Vine_Array::setKey($arr, 'attachments[images][4]', 'dolphins.jpg');
     * ) Vine_Array::setKey($arr, 'attachments[images][5]', 'cats.jpg');
     * )
     * ) // Updated array
     * ) print_r($arr);
     * ---
     * @param   array   The working array. Passed by reference.
     * @param   string  The array key to place value in.
     * @param   string  The value to place inside array key.
     * @return  void
     */
    public static function setKey(array &$arr, $str, $val)
    {
        // Simple key name, set the value and return the array
        if (FALSE === strpos($str, '[')) {
            // Add simple key => value
            $arr[$str] = $val;

            // Finished
            return;
        // Simple key name, set the value and return the array
        } elseif (0 === preg_match_all('/\[(.*?)\]/', $str, $matches)) {
            // Add simple key => value
            $arr[$str] = $val;

            // Finished
            return;
        }

        // Base key
        $key = current(explode('[', $str));

        // Create base key if it doesn't exist in working array
        if ( ! isset($arr[$key])) {
            $arr[$key] = [];
        }

        // Update array, possible recusion
        self::add($arr[$key], $val, $matches[1], count($matches[1]));

        // Finished
        return;
    }

    /**
     * Unset a key in an array, using a string unsetter path.
     * ---
     * ) $arr = [
     * )     'attachments' => [
     * )         'images' => [
     * )             0 => 'whales.jpg',
     * )             1 => 'horses.jpg',
     * )             2 => 'flowers.jpg',
     * )             3 => 'dogs.jpg',
     * )             4 => 'dolphins.jpg',
     * )             5 => 'cats.jpg',
     * )         ],
     * )     ],
     * ) ];
     * )
     * ) // Remove 'dolphins.jpg' and 'cats.jpg'
     * ) Vine_Array::unsetKey($arr, 'attachments[images][4]');
     * ) Vine_Array::unsetKey($arr, 'attachments[images][5]');
     * ---
     * @param   array   The working array. Passed by reference.
     * @param   string  The key to unset.
     * @return  void
     */
    public static function unsetKey(array &$arr, $str)
    {
        // Simple key name, unset key, finished
        if (isset($arr[$str])) {
            unset($arr[$str]);
            return;
        }

        // Key doesn't exist, no need to unset it, finished
        if (0 === preg_match_all('/\[(.*?)\]/', $str, $matches)) {
            return;
        }

        // Base key
        $key = current(explode('[', $str));

        // Base key doesn't exist, no need to unset it, finished
        if ( ! isset($arr[$key])) {
            return;
        }

        // Update array, possible recusion
        self::remove($arr[$key], $matches[1]);

        // Finished
        return;
    }

    /**
     * Support function for setKey(). Recursive.
     * ---
     * @param   array  Working array. Passed by reference.
     * @param   mixed  Value to set in working array. Passed by reference.
     * @param   array  The matches from preg_match_all in setKey().
     * @param   int    The total depth to reach in working array.
     * @param   int    The current working depth in working array.
     * @return  void
     */
    private static function add(&$arr, &$val, $search, $depth, $current = 0)
    {
        // Current array key
        $key = $search[$current];

        // Increment current depth
        $current++;

        // This key doesn't exist or have a value, add it now
        if ( ! isset($arr[$key])) {
            $arr[$key] = $current === $depth ? $val : [];
        }

        // Recursion
        if ($current < $depth) {
            self::add($arr[$key], $val, $search, $depth, $current);
        }
    }

    /**
     * Support function for getKey(). Recursive.
     * ---
     * @param   mixed  Data set to search in.
     * @param   array  The matches from preg_match_all in getKey().
     * @return  mixed  Result of search.
     */
    private static function find($arr, $search)
    {
        // Current search key, remove it from the searches that are left
        $current = array_shift($search);

        // Search failed early, return default value
        if ( ! is_array($arr) && ! empty($search)) {
            return self::RETURN_VALUE;
        // Recursion, keep looking
        } elseif (array_key_exists($current, $arr) && ! empty($search)) {
            return self::find($arr[$current], $search);
        // Search successful, return applicable value
        } elseif (array_key_exists($current, $arr)) {
            return $arr[$current];
        // Search failed, return default value
        } else {
            return self::RETURN_VALUE;
        }
    }

    /**
     * Support function for unsetKey(). Recursive.
     * ---
     * @param   array  Working array. Passed by reference.
     * @param   array  The matches from preg_match_all in unsetKey().
     * @return  void
     */
    private static function remove(&$arr, $search)
    {
        // Current search key, remove it from the searches that are left
        $current = array_shift($search);

        // Recursion
        if ( ! empty($search)) {
            return self::remove($arr[$current], $search);
        }

        // Finished
        unset($arr[$current]);
    }
}
