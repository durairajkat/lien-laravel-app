/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineQuick, vineDate, vineAutoWrap
 */
;(function($) { 'use strict';
    /**
     * Quick helper functions.
     */
    $.vineQuick = {
        /**
         * Helps make "default parameters" in methods and functions more readable.
         * ---
         * @param    mixed  The value actually passed to parent method.
         * @param    mixed  The value to assign if none supplied to parent method.
         * @returns  mixed  The value to utilize in parent method.
         */
        pick : function(inputValue, defaultValue) {
            return (typeof inputValue == 'undefined' ? defaultValue : inputValue);
        },

        /**
         * Round a number. Similar to PHP's round() function.
         * ---
         * @param   int|float  The number to found.
         * @param   int        The number of digits to round to.
         * @return  float      Rounded number.
         */
        round : function(number, precision) {
            number    = parseFloat(number);
            precision = $.vineQuick.pick(precision, 0);
            var multiple  = Math.pow(10, precision);
            var rounded   = Math.round(number * multiple) / multiple;
            return rounded;
        },

        /**
         * Convert a specified value to a boolean.
         * ---
         * @param   mixed  Any value that can be humanly considered a boolean.
         * @return  bool
         */
        toBool : function(input) {
            // Always evaluate undefined as false
            if (typeof input === 'undefined' || input === null) {
                return false;
            }

            // Convert human-readable strings to boolean
            if (typeof input === 'string') {
                switch (input.toLowerCase()) {
                    case 'true' :
                    case 'yes'  :
                    case '1'    :
                        return true;
                    case 'false' :
                    case 'no'    :
                    case '0'     :
                        return false;
                    default:
                        return false;
                }
            }

            // Use native boolean function
            return Boolean(input);
        },

        /**
         * @see  toBool()
         */
        toBoolean : function(input) {
            return this.toBool(input);
        },

        /**
         * See if a specified string is a valid URL.
         * ---
         * @param   string  Input string to check.
         * @return  bool    TRUE if input string a valid URL, FALSE otherwise.
         */
        isUrl : function(input) {
            // Valid URL pattern
            var check = new RegExp('^(https?:\\/\\/)?'          + // Protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // Domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'                       + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'                   + // Port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'                          + // Query string
            '(\\#[-a-z\\d_]*)?$','i');                            // Fragment locator

            // (bool)
            return check.test(input);
        }
    };

    /**
     * Date helper.
     */
    $.vineDate = {
        /**
         * Human-readable two-character days of the week.
         * ---
         * @var  array
         */
        daysShort : ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],

        /**
         * Human-readable days of the week.
         * ---
         * @var  array
         */
        daysLong : [
            'Sunday', 'Monday', 'Tuesday', 'Wednesday',
            'Thursday', 'Friday', 'Saturday'
        ],

        /**
         * Human-readable three-character months.
         * ---
         * @var  array
         */
        monthsShort : [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',
            'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ],

        /**
         * Human-readable months.
         * ---
         * @var  array
         */
        monthsLong : [
            'January', 'February', 'March', 'April', 'May', 'June', 'July',
            'August', 'September', 'October', 'November', 'December'
        ],

        /**
         * Regular expressions used to identify various parts of a date string.
         * ---
         * @var  object
         */
        regexes : {
            'YYYY'   : '([0-9]{4})',
            'YY'     : '([0-9]{2})',
            'MM'     : '(0[1-9]|1[0-2])',
            'M'      : '([1-9]|1[0-2])',
            'DD'     : '(0[1-9]|1[0-9]|2[0-9]|30|31)',
            'D'      : '([1-31]{1,2})',
            'MSHORT' : '([A-Za-z]{3})',
            'MLONG'  : '([A-Za-z]+)'
        },

        /**
         * Convert a PHP-like date format to an internal vineDate format.
         * ---
         * @param   string  The format of the date string, using PHP-like formatting characters.
         * @return  string  A valid vineDate date string format.
         */
        getDateFormat : function(dateFormat) {
            return dateFormat
                .replace('Y', '<!YYYY!>')
                .replace('y', '<!YY!>')
                .replace('M', '<!MSHORT!>')
                .replace('F', '<!MLONG!>')
                .replace('m', '<!MM!>')
                .replace('n', '<!M!>')
                .replace('d', '<!DD!>')
                .replace('j', '<!D!>');
        },

        /**
         * Get a regex pattern from a PHP-like date format, which can be used to identify
         * various aspects of a compatible date string.
         * ---
         * @return  string  A valid regex pattern.
         */
        getDatePattern : function(dateFormat) {
            return this.getDateFormat(dateFormat)
                .replace('<!YYYY!>', this.regexes.YYYY)
                .replace('<!YY!>', this.regexes.YY)
                .replace('<!MSHORT!>', this.regexes.MSHORT)
                .replace('<!MLONG!>', this.regexes.MLONG)
                .replace('<!MM!>', this.regexes.MM)
                .replace('<!M!>', this.regexes.M)
                .replace('<!DD!>', this.regexes.DD)
                .replace('<!D!>', this.regexes.D);
        },

        /**
         * Convert a date string to a javascipt Date() object. If the provided date string is not
         * able to be parsed using the provided date format, a Date() object with the current date
         * will be returned.
         * ---
         * @param   string  The format of the date string, using PHP-like formatting.
         * @param   string  The date string to parse using the provided format.
         * @return  object  An instance of Date().
         */
        strToDate : function(dateFormat, dateString) {
            // Local vars
            var year    = new Date().getFullYear();
            var month   = new Date().getMonth();
            var day     = new Date().getDate();
            var format  = this.getDateFormat(dateFormat);
            var pattern = this.getDatePattern(dateFormat);

            // Prepare to look for the order of the specific parts of the date string
            var order    = {'year' : null, 'month' : null, 'day' : null};
            var internal = /<!(\w+)!>/g;
            var matches;
            var i = 1;

            // Compile the order the specific parts of the date appear in the string
            while (null !== (matches = internal.exec(format))) {
                // The next item in pattern is the year
                if ('YYYY' === matches[1] || 'YY' === matches[1]) {
                    order.year = i;
                // The next item in pattern is the month
                } else if (
                       'MSHORT' === matches[1]
                    || 'MLONG'  === matches[1]
                    || 'MM'     === matches[1]
                    || 'M'      === matches[1]
                ) {
                    order.month = i;
                // The next item in pattern is the day of the month
                } else if ('DD' === matches[1] || 'D' === matches[1]) {
                    order.day = i;
                }

                // Increment the order for each match
                i++;
            }

            // Parse the date string and get individual parts of the date
            var queue  = new RegExp(pattern, 'i');
            var search = queue.exec(dateString);

            // Date string could not be matched with the provided date format, stop here
            if (null === search) {
                return new Date(year, month, day);
            }

            // Try to be smart and see if parsed 2 digit year is from past century
            if (null !== order.year && 2 === search[order.year].length) {
                // Into to attempt to determine if year is from the last century or this one
                var century     = year.toString().substring(0, 2);
                var searchYear  = search[order.year];
                var thisCentury = parseInt(century + searchYear);
                var lastCentury = parseInt((century - 1) + searchYear);

                /**
                 * For 2 Digit Years
                 *
                 * For the most accurate results, 4 digit years should always be used. It's
                 * difficult to determine the century a date is referencing when only 2 digits
                 * are provided. If the 2-digit year provided is greater than the current
                 * 2-digit year + 5 years, it will be assumed that the year being referenced
                 * is from the previous century. 5 years leeway is given for this century to
                 * account for commonly used 2-digit years for dates associated with credit
                 * card expiration dates, bank statements, check numbers, scheduling,
                 * presidential elections (tsk-tsk), etc.
                 *
                 * As of 2012 when this was written:
                 * - For the year of 87, assume 1987.
                 * - For the year of 11, assume 2011.
                 * - For the year of 17, assume 2017.
                 * - For the year of 18, assume 1918.
                 */

                // Assume parsed year is last century if it's greater than 5 years from now
                if ((year + 5) < thisCentury) {
                    year = lastCentury;
                // Assume year is this century
                } else {
                    year = thisCentury;
                }
            // Use parsed year as is
            } else if (null !== order.year) {
                year = search[order.year];
            }

            // Get numeric value of 3-letter month or full month name
            if (null !== order.month && isNaN(search[order.month])) {
                // Loop through 3-letter names until parsed month is found, save numeric value
                if (3 === search[order.month].length) {
                    for (i = 0; i < this.monthsShort.length; i++) {
                        if (search[order.month] === this.monthsShort[i]) {
                            month = i; break;
                        }
                    }
                // Loop through full month names until parsed month is found, save numeric value
                } else {
                    for (i = 0; i < this.monthsLong.length; i++) {
                        if (search[order.month] === this.monthsLong[i]) {
                            month = i; break;
                        }
                    }
                }
            // Remove leading 0 from parsed month if applicable
            } else if (null !== order.month && '0' === search[order.month].charAt(0)) {
                month = search[order.month].substr(1) - 1; // Index starts at 0
            // Use parsed month as is
            } else if (null !== order.month) {
                month = search[order.month] - 1; // Index starts at 0
            }

            // Remove leading 0 from day if applicable
            if (null !== order.day && '0' === search[order.day].charAt(0)) {
                day = search[order.day].substr(1);
            // Used parsed day as is
            } else if (null !== order.day) {
                day = search[order.day];
            }

            // The Date() object with (hopefully) the correct parsed date
            return new Date(year, month, day);
        },

        /**
         * Convert a javascipt Date() object to a human-readable date string. If the provided date
         * format is not valid, a default Y-m-d formatted result will be returned.
         * ---
         * @param   string  The format of the date string, using PHP-like formatting.
         * @param   object  The Date() object to parse.
         * @return  string  A human-readable date string.
         */
        dateToStr : function(dateFormat, dateObj) {
            return this.getDateFormat(dateFormat)
                .replace('<!YYYY!>', dateObj.getFullYear().toString())
                .replace('<!YY!>', dateObj.getFullYear().toString().substring(2, 4))
                .replace('<!MSHORT!>', this.monthsShort[dateObj.getMonth()])
                .replace('<!MLONG!>', this.monthsLong[dateObj.getMonth()])
                .replace('<!MM!>', String('0' + (dateObj.getMonth() + 1)).slice(-2))
                .replace('<!M!>', dateObj.getMonth() + 1)
                .replace('<!DD!>', String('0' + dateObj.getDate()).slice(-2))
                .replace('<!D!>', dateObj.getDate());
        },

        /**
         * Get the day of the week (Monday, Tuesday, etc.) from a specified Date object.
         * ---
         * @param   object|int  Instance of Date() or 0-6.
         * @param   bool        (opt) Get short name? Defaults to FALSE.
         * @return  string
         */
        getDayName : function(input, shortName) {
            if ( ! shortName) {
                if ('object' === typeof input) {
                    return this.daysLong[input.getDay()];
                } else {
                    return this.daysLong[input];
                }
            } else {
                if ('object' === typeof input) {
                    return this.daysShort[input.getDay()];
                } else {
                    return this.daysShort[input];
                }
            }
        },

        /**
         * Get the month name (March, April, etc.) from a specified Date object.
         * ---
         * @param   object|int  Instance of Date() or 0-11.
         * @param   bool        (opt) Get short name? Defaults to FALSE.
         * @return  string
         */
        getMonthName : function(input, shortName) {
            if ( ! shortName) {
                if ('object' === typeof input) {
                    return this.monthsLong[input.getMonth()];
                } else {
                    return this.monthsLong[input];
                }
            } else {
                if ('object' === typeof input) {
                    return this.monthsShort[input.getMonth()];
                } else {
                    return this.monthsShort[input];
                }
            }
        }
    };

    /**
     * Automatically wrap every X elements in a specified tag.
     * ---
     * @param   int     Wrap increment.
     * @param   string  The HTML tag to wrap elements in.
     * @return  elem    Instance of self for chainability.
     */
    $.fn.vineAutoWrap = function(count, tag) {
        // Local vars
        var length = this.length;
        var name   = typeof tag === 'string' ? tag : '<div />';
        var elem   = $(name);

        // Remove existing wrappers if applicable (@todo improve result consistency)
        if (typeof tag === 'string' && this.parent($(elem).prop('tagName')).length > 0) {
            this.unwrap();
        }

        // Loop through all elements and wrap with specified class every X increment
        for (var i = 0; i < length ; i += count) {
            this.slice(i, i + count).wrapAll(name);
        }

        // Maintain chainability
        return this;
    };

    /**
     * Run a specified function.
     * ---
     * @param   string  function() or namespace.function().
     * @param   mixed   Namespace.
     * @return  mixed
     * @see     http://stackoverflow.com/questions/359788/how-to-execute-a-javascript-function-when-i-have-its-name-as-a-string
     */
    $.vineRun = function(func, context) {
        try {
            var args  = [].slice.call(arguments).splice(2);
            var names = func.split('.');
            func  = names.pop();

            for (var i = 0; i < names.length; i++) {
                context = context[names[i]];
            }

            return '' === func
                ? undefined
                : context[func].apply(context, args);
        } catch (e) {
            $.vine.log(func + ' is undefined');
        }
    };
})(jQuery);