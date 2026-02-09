/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    $.fn.val() override
 */
;(function($) { 'use strict';
    /**
     * Original jQuery val() method.
     */
    var jqueryVal = $.fn.val;

    /**
     * Override jQuery's native val() method.
     * ---
     * @return  mixed  Value from jQuery's native val() method.
     */
    $.fn.val = function() {
        // Execute original jQuery val() method
        var result = jqueryVal.apply(this, arguments);

        // Most VineUI widgets have a 'refresh' listener event
        $(this).trigger('refresh');

        // Return result from original jQuery val() method
        return result;
    };
})(jQuery);