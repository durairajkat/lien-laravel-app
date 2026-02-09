/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vinePluginName
 */
;(function($) { 'use strict';
    /**
     * Base plugin class.
     * @var  object
     */
    var plugin = {
        /**
         * Plugin's configuration.
         * @var  object
         * @required
         */
        cfg : undefined,

        /**
         * Plugin's parent element.
         * @var  element
         * @required
         */
        pe : undefined,

        /**
         * Unique ID of the compiled element.
         * @var  int
         * @required
         */
        id : undefined,

        /**
         * Class constructor.
         * @required
         */
        _start : function() {
        }
    };

    /**
     * Setup compiler for plugin.
     * @var  object
     */
    var setup = {
        /**
         * Convert element's data attributes to configuration settings.
         * @param   object   Base configuration object.
         * @param   element  The element to get data attributes from.
         * @return  object   Updated configuration object
         */
        dataToCfg : function(cfg, el) {
            // (object) Updated configuration object
            return cfg;
        },

        /**
         * Compile input option configuration.
         * @param   object|string  Custom configuration array or method call.
         * @return  object         String if method call. Object otherwise.
         */
        optionsToCfg : function(options) {
            // (object) Sanitized input option configuration
            return options;
        }
    };

    /**
     * Plugin constructor.
     * @param   mixed
     * @return  object
     */
    $.fn.vinePluginName = function(options) {
        /**
         * Plugin's default configuration.
         * @var  object
         */
        var cfg = setup.dataToCfg({}, this);

        // Construct plugin
        return $.vine.factory({
            name    : 'vinePluginName',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : setup.optionsToCfg(options),
            args    : arguments
        });
    };
})(jQuery);