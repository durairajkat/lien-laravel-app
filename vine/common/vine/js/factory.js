/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vine
 */
;(function($) { 'use strict';
    /**
     * Build blueprint for factory.
     * ---
     * @var  object
     */
    var blueprint = {
        name    : '',        // (string)  Plugin name
        cfg     : {},        // (object)  Plugin's default configuration
        caller  : {},        // (object)  Plugin constructor with _compile() and _activate()
        el      : undefined, // (element) DOM element plugin is attached to
        arg     : undefined, // (mixed)   First argument passed to factory
        args    : undefined  // (object)  All arguments passed to factory
    };

    /**
     * VineUI base. Base dependency for all VineUI widgets.
     */
    $.vine = {
        /**
         * Make a random string of a specified length.
         * ---
         * @param   int
         * @return  string
         */
        makeRandomString : function(length) {
            var chars  = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var result = '';

            for (var i = 0; i < length; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            return result;
        },

        /**
         * Make a unique ID to assign to a dynamically generated element.
         * ---
         * @param   sting   The prefix for the ID. Must NOT start with a number.
         * @return  string  A unique ID.
         */
        makeUniqueId : function(prefix) {
            return [prefix, + new Date(), this.makeRandomString(7)].join('-');
        },

        /**
         * IE friendly console log.
         * ---
         * @return  void
         */
        log : function() {
            // Create fake console function for IE
            if ( ! window.console.log.apply) {
                return;
            }

            // Run console as normal
            console.log.apply(console, arguments);
        },

        /**
         * Plugin factory. Base constructor for all VineUI plugins and widgets.
         * ---
         * @param   object  Factory building instructions.
         * @return  object  Instance of calling element to maintain chainability.
         */
        factory : function(build) {
            // Local vars
            var setup      = $.extend({}, blueprint, build);
            var methodCall = typeof setup.arg === 'string';
            var args       = Array.prototype.slice.call(arguments);
            var result     = setup.el;

            // Prevents calls to private methods
            if (methodCall && '_' === setup.arg.charAt(0)) {
                return result;
            }

            // Remove the method call name from the arguments list
            args.shift(args, 0);

            // Call a method for this instance
            if (methodCall) {
                // Loop through this object
                result.each(function() {
                    // Get data for this instance
                    var instance = $(result).data(setup.name);

                    // Call the method
                    var methodValue = instance && $.isFunction(instance[setup.arg])
                                    ? instance[setup.arg].apply(instance, args)
                                    : instance;

                    // Method result
                    if (methodValue !== instance && methodValue !== undefined) {
                        result = methodValue;
                        return false;
                    }
                });
            // Setup a new instance
            } else {
                // Loop through each element and create instance for it
                result.each(function() {
                    // Don't initialize more than once
                    if (undefined === $(this).data(setup.name)) {
                        // Initialize plugin
                        var plugin = $.extend({}, setup.caller);
                        plugin.cfg = $.extend({}, setup.cfg, setup.arg);
                        plugin.id  = $.vine.makeUniqueId(setup.name);
                        plugin.pe  = $(this);

                        // Save plugin to element's data
                        $(this).data(setup.name, plugin);
                        $(this).attr('data-id', plugin.id);
                        $(this).attr('data-' + setup.name, true);

                        // Call plugin constructor
                        plugin._start();
                    }
                });
            }

            // Maintain chainability
            return result;
        }
    };
})(jQuery);