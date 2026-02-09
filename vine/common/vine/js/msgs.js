/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineSuccessMsg, vineErrorMsg, vineErrorFields
 */
;(function($) { 'use strict';
    /**
     * Success message plugin.
     * ---
     * @param   string    The message to compile.
     * @param   int|bool  If > 0, milliseconds till auto-hide. 0 or FALSE = no auto-hide.
     * @param   bool      If TRUE, message is appended to elements; prepended otherwise.
     * @return  object    Instance of self for chainability.
     */
    $.fn.vineSuccessMsg = function(textual, autoHide, append) {
        // Local vars
        var self = this;
        var msg  = '<div class="alert success vine-success-msg">' + textual + '</div>';
        autoHide = $.vineQuick.pick(autoHide, 5000);
        append   = $.vineQuick.pick(append, false);

        // Destroy existing success, error messages, and error field highlights
        $('.vine-success-msg, .vine-error-msg').remove();
        $('.vine-error-field').removeClass('vine-error-field');

        // Remove previous message timeout
        self.msgTimeout = clearTimeout(this.timeout);

        // Loop through each matching element
        $(self).each(function() {
            // Append message to element
            if (true === append) {
                $(this).append(msg);
            // Prepend message to element
            } else {
                $(this).prepend(msg);
            }
        });

        // Automatically hide message after specified interval
        if (autoHide > 0) {
            self.msgTimeout = setTimeout(function() {
                $(self).find('.vine-success-msg').fadeOut(400, function() {
                    $(this).remove();
                });
            }, autoHide);
        }

        // Maintain chainability
        return self;
    };

    /**
     * Error message plugin.
     * ---
     * @param   string    The message to compile.
     * @param   object    Field specific errors.
     * @param   int|bool  If > 0, milliseconds till auto-hide. 0 or FALSE = no auto-hide.
     * @param   bool      If TRUE, message is appended to elements; prepended otherwise.
     * @return  object    Instance of self for chainability.
     */
    $.fn.vineErrorMsg = function(textual, fields, autoHide, append) {
        // Local vars
        var self   = this;
        var markup = '';
        var msg    = $.vineQuick.pick(textual, false);
        fields     = $.vineQuick.pick(fields, false);
        autoHide   = $.vineQuick.pick(autoHide, false);
        append     = $.vineQuick.pick(append, false);


        // Auto-hide was put into 'fields' parameter, swap parameters as needed
        if ( ! isNaN(parseInt(fields))) {
            append   = autoHide; // fields   -> autoHide
            autoHide = fields;   // autoHide -> append
        }

        // Destroy existing success, error messages, and error field highlights
        $('.vine-success-msg, .vine-error-msg').remove();
        $('.vine-error-field').removeClass('vine-error-field');

        // No error message to compile, stop here, maintain chainability
        if (false === msg && (false === fields || $.isEmptyObject(fields))) {
            return self;
        }

        // Start compilng error message
        markup += '<div class="alert failure vine-error-msg">';

        // Compile primary message
        if (false !== msg) {
            markup += msg;
        }

        // Loop through and compile field-specific errors
        if ( ! $.isEmptyObject(fields)) {
            // Begin list
            markup += '<ul>';

            // Loop through and compile each field error
            for (var k in fields) {
                markup += '<li>' + fields[k] + '</li>';
            }

            // End list
            markup += '</ul>';
        }

        // Finish compiling error message
        markup += '</div>';

        // Remove previous message timeout
        self.msgTimeout = clearTimeout(this.timeout);

        // Loop through each matching element
        $(self).each(function() {
            // Append message to element
            if (true === append) {
                $(this).append(markup);
            // Prepend message to element
            } else {
                $(this).prepend(markup);
            }
        });

        // Automatically hide message after specified interval
        if (autoHide > 0) {
            self.msgTimeout = setTimeout(function() {
                $(self).find('.vine-error-msg').fadeOut(400, function() {
                    $(this).remove();
                });
            }, autoHide);
        }

        // Maintain chainability
        return self;
    };

    /**
     * Error message plugin constructor.
     * ---
     * @param   object    Field specific error messages.
     * @param   int|bool  If > 0, milliseconds till auto-hide. 0 or FALSE = no auto-hide.
     * @return  object    Instance of self for chainability.
     */
    $.fn.vineErrorFields = function(errors, autoHide) {
        // Local vars
        var self = this;
        errors   = $.vineQuick.pick(errors, {});
        autoHide = $.vineQuick.pick(autoHide, false);

        // Remove existing field error highlights
        $('.vine-error-field').removeClass('vine-error-field');

        // No error highlights, stop here and return self to maintain chainability
        if ('object' !== typeof errors || $.isEmptyObject(errors)) {
            return self;
        }

        // Remove previous message timeout
        self.fieldTimeout = clearTimeout(this.timeout);

        // Loop through each matching element
        $(self).each(function() {
            // Local vars
            var el  = this;
            var msg = null;

            // Loop through each field to highlight
            for (var k in errors) {
                // This error message (not utilized at this time)
                msg = errors[k];

                // Highlight this field
                $(el).find('*[name^=' + k + ']')
                     .addClass('vine-error-field')
                     .parent('.vine-select, .vine-file')
                     .addClass('vine-error-field');
                $(el).find('*[title^=' + k + ']')
                     .addClass('vine-error-field');
            }
        });

        // Automatically hide message after specified interval
        if (autoHide > 0) {
            self.fieldTimeout = setTimeout(function() {
                $(self).find('.vine-error-field').removeClass('vine-error-field');
            }, autoHide);
        }

        // Maintain chainability
        return self;
    };
})(jQuery);