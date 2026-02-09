<?php

/**
 * Date & Timestamp Manipulation
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Date
{
    /**
     * Display a timestamp in user's local timezone.
     * ---
     * @param   string  The date format. Identical to PHP's date() function.
     * @param   mixed   Timestamp or date string to convert
     * @return  string  Timestamp in specified format, in desired timezone.
     */
    public static function show($format, $stamp = NULL)
    {
        // Timestamp provided?
        $stamp = NULL === $stamp ? time() : $stamp;

        // Get the converted timestamp
        $stamp = self::getZoneTime($stamp, Vine_Session::getUserZone(), date('T'));

        // (string) Converted timestamp in applicable format
        return date($format, $stamp);
    }

    /**
     * Converts a date/time to a specified timezone and returns a new unix timestamp. If
     * the third paramater is not provided ($from), the system's local timezone
     * (date.timezone) will be used as the baseline, and it will be assumed that the input
     * date was created using the system's local timezone.
     * ---
     * ) // Convert a UTC timestamp (i.e. from database) to EDT (America/New_York)
     * ) $stamp = Vine_Date::getZoneTime('2012-01-23 08:36:00', 'America/New_York', 'UTC');
     * )
     * ) // January 23, 2012, 3:36 AM
     * ) echo date('F j, Y, g:i A', $stamp);
     * ---
     * @param   mixed   Stored timestamp.
     * @param   string  User's timezone or timezone offset.
     * @param   string  [optional] Timestamp's timezone. Defaults to system's timezone.
     * @return  int     Unix timestamp
     * @see     http://php.net/manual/en/timezones.php
     */
    public static function getZoneTime($date, $to, $from = NULL)
    {
        // Use system's timezone
        if (NULL === $from) {
            $from = date_default_timezone_get();
        }

        // Work with unix timestamp
        if (is_numeric($date)) {
            $date = date('Y-m-d H:i:s', $date);
            $time = strtotime($date);
        // Work with string date
        } else {
            $date = date('Y-m-d H:i:s', strtotime($date));
            $time = strtotime($date);
        }

        // Using timezone offset (less accurate)
        if (is_numeric($from)) {
            $fromDiff = $from;
        // Using timezone name (more accurate)
        } else {
            $fromZone = new DateTimeZone($from);
            $fromTime = new DateTime($date, $fromZone);
            $fromDiff = $fromZone->getOffset($fromTime);
        }

        // Using timezone offset (less accurate)
        if (is_numeric($to)) {
            $toDiff = $to;
        // Using timezone name (more accurate)
        } else {
            // Get $to timezone, offset, and difference
            $toZone = new DateTimeZone($to);
            $toTime = new DateTime($date, $toZone);
            $toDiff = $toZone->getOffset($toTime);
        }

        // $date -> user's timezone -> unix timestamp
        return $time - ($fromDiff - $toDiff);
    }

    /**
     * Get the offset for a specified timezone.
     * ---
     * @param   string  Timezone to get offset for.
     * @param   string  [optional] Base timezone. Default = 'UTC'.
     * @return  int     Timezone offset in seconds.
     */
    public static function getZoneOffset($to, $from = 'UTC')
    {
        $fromDtz = new DateTimeZone($from);
        $toDtz   = new DateTimeZone($to);
        $fromDt  = new DateTime('now', $fromDtz);
        $toDt    = new DateTime('now', $toDtz);
        return $fromDtz->getOffset($fromDt) + $toDtz->getOffset($toDt);
    }

    /**
     * Get an array containing all of the months in a year, alongside the applicable
     * numeric value. Language according to current locale. Commonly used for form
     * <select> dropdowns.
     * ---
     * ) [
     * )     [01] => 'Dec',
     * )     [02] => 'Jan',
     * )     [03] => 'Feb',
     * )     [04] => 'Mar',
     * )     [05] => 'Apr',
     * )     [06] => 'May',
     * )     [07] => 'Jun',
     * )     [08] => 'Jul',
     * )     [09] => 'Aug',
     * )     [10] => 'Sep',
     * )     [11] => 'Oct',
     * )     [12] => 'Nov',
     * ) ];
     * ---
     * @param   string  Date format, according to strftime().
     * @return  array
     */
    public static function getMonths($format = '%m - %b')
    {
        // Start with an empty array
        $result = [];

        // Loop through all 12 months
        for ($i = 1; $i <= 12; $i++) {
            // Ensure each month's numeric value is two digits
            $m = str_pad($i, 2, '0', STR_PAD_LEFT);

            // Add to array
            $result[$m] = strftime($format, mktime(0, 0, 0, $i, 1, 2001));
        }

        // (array) MM => 'Month'
        return $result;
    }

    /**
     * Get an array containing a set of years. Commonly used for form <select> dropdowns.
     * ---
     * ) [
     * )     2001 => 2001,
     * )     2002 => 2002,
     * )     2003 => 2003,
     * )     2004 => 2004,
     * ) ];
     * ---
     * @param   int    The number of years before current year.
     * @param   int    The number of years after current year.
     * @return  array  Array containing years.
     */
    public static function getYears($before = 5, $after = 5)
    {
        // Start & end years
        $from = date('Y') - $before;
        $to   = date('Y') + $after;

        // Start with an empty array
        $result = [];

        // Loop through each year, and add to array
        for ($y = $from; $y <= $to; $y++) {
            $result[$y] = $y;
        }

        // (array) year => year
        return $result;
    }

    /**
     * Convert a Microsoft Excel date to a unix timestamp.
     * ---
     * @param   mixed  Excel timestamp.
     * @param   bool   The Mac version of Excel stores dates differently.
     * @return  int    Unix timestamp.
     */
    public static function excelToUnix($timestamp, $mac = FALSE)
    {
        /*
         * Days between Excel start date and Unix start date.
         * ---
         * ) PC versions of Excel start on Jan 1st, 1900.
         * ) Mac versions of Excel start on Jan 1st, 1904.
         * ) Unix time starts on Jan 1st, 1970.
         */
        $days = FALSE === $mac ? 25569 : 24107;

        // int/string/float -> float
        $timestamp = (float) $timestamp;

        // Date & time
        if (FALSE !== strpos($timestamp, '.')) {
            $date = explode('.', $timestamp);
        // Date, no time
        } else {
            $date[0] = $timestamp;
            $date[1] = 0;
        }

        // Excel format: date.time (date = days, time = fraction of day)
        $time = (int) round(('0.' . $date[1]) * 86400, 0);
        $date = (int) ($date[0] - $days) * 86400;
        return $date + $time;
    }

    /**
     * Convert a unix timestamp or valid date string to a Microsoft Excel date.
     * ---
     * @param   mixed
     * @param   bool
     * @return  float
     */
    public static function unixToExcel($timestamp, $mac = FALSE)
    {
        /*
         * Days between Excel start date and Unix start date.
         * ---
         * ) PC versions of Excel start on Jan 1st, 1900.
         * ) Mac versions of Excel start on Jan 1st, 1904.
         * ) Unix time starts on Jan 1st, 1970.
         */
        $days = FALSE === $mac ? 25569 : 24107;

        // Date string
        if ( ! is_int($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        // Excel format: date.time (date = days, time = fraction of day)
        return round(($timestamp / 86400) + $days, 8);
    }
}
