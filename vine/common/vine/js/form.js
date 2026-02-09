/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineForm
 */
;(function($) { 'use strict';
    /**
     * Base plugin class.
     */
    var plugin = {
        /**
         * Plugin's configuration.
         * ---
         * @var  object
         * @required
         */
        cfg : undefined,

        /**
         * Plugin's parent element.
         * ---
         * @var  element
         * @required
         */
        pe : undefined,

        /**
         * Unique ID of the compiled element.
         * ---
         * @var  int
         * @required
         */
        id : undefined,

        /**
         * Class constructor. Activate form.
         * ---
         * @required
         */
        _start : function() {
            // Local vars
            var self = this;
            var pe   = self.pe;

            // When form is submitted
            $(pe).submit(function(e) {
                e.preventDefault();
                self.doSubmit();
            });
        },

        /**
         * Handle a failed AJAX response. Failed responses can include include 404s,
         * timeouts, form errors, etc.
         * ---
         * @param   object  The JSON data returned in AJAX response (if applicable).
         * @return  void
         */
        _handleError : function(data) {
            // Local vars
            var self = this;
            var cfg  = self.cfg;
            var pe   = self.pe;
            var hide = cfg.errorAutoHide;
            var box  = false === cfg.msgContainer ? pe : cfg.msgContainer;

            // Run onError() function directly
            if ('function' === typeof cfg.onError) {
                cfg.onError.apply($(pe), data);
                $(pe).trigger('formError');
            // Run onError() function by reference
            } else if ('string' === typeof cfg.onError) {
                $.vineRun(cfg.onError, window, data);
                $(pe).trigger('formError');
            }

            // Never redirect on error
            if (false === cfg.errorRedirect) {
                // Show error messages
                $(box).vineErrorMsg(data.message, data.errors, hide);
                $(pe).vineErrorFields(data.errors, hide);


            // Do whatever the backend says to do
            } else if (true === cfg.errorRedirect) {
                window.location = data.url;
            // Go to specified URL regardless of what backend instructs
            } else {
                window.location = cfg.errorRedirect;
            }
        },

        /**
         * Handle a 100% successful AJAX response.
         * ---
         * @param   object  The JSON data returned in AJAX response.
         * @return  void
         */
        _handleSuccess : function(data) {
            // Local vars
            var self = this;
            var cfg  = self.cfg;
            var pe   = self.pe;
            var hide = cfg.successAutoHide;
            var box  = false === cfg.msgContainer ? pe : cfg.msgContainer;

            // Run onSuccess() function directly
            if ('function' === typeof cfg.onSuccess) {
                cfg.onSuccess.apply($(pe), data);
                $(pe).trigger('formSuccess');
            // Run onSuccess() function by reference
            } else if ('string' === typeof cfg.onSuccess) {
                $.vineRun(cfg.onSuccess, window, data);
                $(pe).trigger('formSuccess');
            }

            // Never redirect on success
            if (false === cfg.successRedirect) {
                $(box).vineSuccessMsg(data.message, hide);
            // Do whatever the backend says to do
            } else if (true === cfg.successRedirect) {
                window.location = data.url;
            // Go to specified URL regardless of what backend instructs
            } else {
                window.location = cfg.successRedirect;
            }
        },

        /**
         * Submit the form.
         * ---
         * @return  void
         */
        doSubmit : function() {
            // Local vars
            var self = this;
            var cfg  = self.cfg;
            var pe   = self.pe;
            var action = $(pe).attr('action');

            // Run onSubmit() function directly
            if ('function' === typeof cfg.onSubmit) {
                cfg.onSubmit.apply($(pe), arguments);
                $(pe).trigger('formSubmit');
            // Run onSubmit() function by reference
            } else if ('string' === typeof cfg.onSubmit) {
                $.vineRun(cfg.onSubmit, window);
                $(pe).trigger('formSubmit');
            }

            // Append extra stuff to action URL with '&'
            if (action.indexOf('?') > 0) {
                action += '&_success_redirect=' + cfg.successRedirect
                        + '&_error_redirect='   + cfg.errorRedirect
                        + '&json=1';
            // Append extra stuff to action URL with '?'
            } else {
                action += '?_success_redirect=' + cfg.successRedirect
                        + '&_error_redirect='   + cfg.errorRedirect
                        + '&json=1';
            }

            // Submit the form
            $(pe).ajaxSubmit({
                dataType : 'json',
                url      : action,
                success  : function(data) {
                    if (undefined === data.status || ! data.status) {
                        self._handleError(data);
                    } else {
                        self._handleSuccess(data);
                    }
                },
                error : function(data) {
                    self._handleError(data);
                }
            });
        },
    };

    /**
     * Setup compiler for plugin.
     */
    var setup = {
        /**
         * Convert element's data attributes to configuration settings.
         * ---
         * @param   object   Base configuration object.
         * @param   element  The element to get data attributes from.
         * @return  object   Updated configuration object
         */
        dataToCfg : function(cfg, el) {
            // Local vars
            var self = this;

            // Update plugin's success-auto-hide setting
            if (undefined !== $(el).data('success-auto-hide')) {
                cfg.successAutoHide = isNaN(parseInt($(el).data('success-auto-hide')))
                                    ? $.vineQuick.toBool($(el).data('success-auto-hide'))
                                    : parseInt($(el).data('success-auto-hide'));
            }

            // Update plugin's success-redirect setting
            if (undefined !== $(el).data('success-redirect')) {
                cfg.successRedirect = self._getRedirect($(el).data('success-redirect'));
            }

            // Update plugin's error-auto-hide setting
            if (undefined !== $(el).data('error-auto-hide')) {
                cfg.errorAutoHide = isNaN(parseInt($(el).data('error-auto-hide')))
                                  ? $.vineQuick.toBool($(el).data('error-auto-hide'))
                                  : parseInt($(el).data('error-auto-hide'));
            }

            // Update plugin's error-redirect setting
            if (undefined !== $(el).data('error-redirect')) {
                cfg.errorRedirect = self._getRedirect($(el).data('error-redirect'));
            }

            // Update plugin's error-default setting
            if (undefined !== $(el).data('error-default')) {
                cfg.errorDefault = $(el).data('error-default');
            }

            // Update plugin's scroll-to-msg setting
            if (undefined !== $(el).data('scroll-to-msg')) {
                cfg.scrollToMsg = $.vineQuick.toBool($(el).data('scroll-to-msg'));
            }

            // Update plugin's msg-container setting
            if (undefined !== $(el).data('msg-container')) {
                cfg.msgContainer = $('#' + $(el).data('msg-container')).length > 0
                                 ? ('#' + $(el).data('msg-container'))
                                 : false;
            }

            // Update plugin's on-submit function or function name
            if (undefined !== $(el).data('call-submit')) {
                cfg.onSubmit = $(el).data('call-submit');
            }

            // Update plugin's on-error function or function name
            if (undefined !== $(el).data('call-error')) {
                cfg.onError = $(el).data('call-error');
            }

            // Update plugin's on-success function or function name
            if (undefined !== $(el).data('call-success')) {
                cfg.onSuccess = $(el).data('call-success');
            }

            // (object) Updated configuration object
            return cfg;
        },

        /**
         * Compile input option configuration.
         * ---
         * @param   object|string  Custom configuration array or method call.
         * @return  object         String if method call. Object otherwise.
         */
        optionsToCfg : function(options) {
            // (object) Sanitized input option configuration
            return options;
        },

        /**
         * Parse redirect URL setting.
         * ---
         * @param   string
         * @return  string|bool
         */
        _getRedirect : function(input) {
            // Standardize
            var check = input.toString().toLowerCase();

            // Redirect to URL given in AJAX response
            if (   'true' === check
                || 'yes'  === check
                || 'y'    === check
                || '1'    === check
                || 'auto' === check
            ) {
                return true;
            // Don't redirect to a URL
            } else if (
                   'false' === check
                || 'no'    === check
                || 'n'     === check
                || '0'     === check
            ) {
                return false;
            // Redirect to specified URL
            } else {
                return input;
            }
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineForm = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = setup.dataToCfg({
            successAutoHide : 4000,              // Automatically hide successes? (0 or FALSE = no, int = auto-hide duration)
            successRedirect : true,              // Redirect on success? (FALSE = no, TRUE = response URL, string = exact URL)
            errorAutoHide   : 0,                 // Automatically hide errors? (0 or FALSE = no, int = auto-hide duration)
            errorRedirect   : false,             // Redirect on error? (FALSE = no, TRUE = response URL, string = exact URL)
            errorDefault    : 'Invalid Request', // Default error message to use if form submission fails
            scrollToMsg     : true,              // Automatically scroll to error/success messages?
            msgContainer    : false,             // ID to put messages inside of (when FALSE, prepended to form)
            onSubmit        : function(data) {}, // Called right before AJAX request (form submission)
            onError         : function(data) {}, // Called when form gets unsuccessful AJAX response
            onSuccess       : function(data) {}  // Called when form gets successful AJAX response
        }, this);

        // Construct plugin
        return $.vine.factory({
            name    : 'vineForm',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : setup.optionsToCfg(options),
            args    : arguments
        });
    };
})(jQuery);