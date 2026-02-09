/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineFile
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
         * Wrapper element.
         * ---
         * @var  element
         */
        we : undefined,

        /**
         * Class constructor.
         * ---
         * @required
         */
        _start : function() {
            this._compile();
            this._activate();
        },

        /**
         * Compile the markup for the fake <input type="file" /> field.
         * ---
         * @return  void
         */
        _compile : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element

            // Fake text holder and styles
            var markup = '<span class="vine-text">' + cfg.fieldText + '</span>'
                       + '<span class="vine-button select-file">'
                       + cfg.buttonText
                       + '</span>';

            // Wrap real input and stylize
            $(pe).wrap('<div class="vine-file" id="' + this.id + '" />').after(markup);

            // Save wrapper element
            self.we = $(pe).parent();

            // Duplicate <select>'s margins on wrapper
            $(self.we).css({
                'margin-top'    : $(pe).css('margin-top'),
                'margin-right'  : $(pe).css('margin-right'),
                'margin-bottom' : $(pe).css('margin-bottom'),
                'margin-left'   : $(pe).css('margin-left')
            });

            // Remove <select>'s margins
            $(pe).attr('style', function(i, s) {
                return 'margin: 0 !important;' + (undefined === s ? '' : s);
            });
        },

        /**
         * Activate the fake <input type="file" /> field.
         * ---
         * @return  void
         */
        _activate : function() {
            // Local vars
            var self = this;

            // Show file name whenever a file is selected
            $(self.pe).bind('change', function() {
                $('#' + self.id).find('.vine-text').text(
                    self._parseFileName($(self.pe).val())
                );
            });

            // When mouseover on real file input
            $(self.pe).hover(
                function() { $('#' + self.id).addClass('vine-focus'); },
                function() { $('#' + self.id).removeClass('vine-focus'); }
            );

            // When the real file input is in focus (clicked on, tabbed into, etc.)
            $(self.pe).focus(function() {
                $('#' + self.id).addClass('vine-focus');
            });

            // When the real file input is blurred (tabbed out, clicked elsewhere, etc.)
            $(self.pe).blur(function() {
                $('#' + self.id).removeClass('vine-focus');
            });
        },

        /**
         * Get the name of the selected file.
         * ---
         * @return  string
         */
        _parseFileName : function(name) {
            return name.split(/(\\|\/)/g).pop();
        }
    };

    /**
     * Setup compiler for plugin.
     * ---
     * @var  object
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
            // Update plugin's default button text
            if (undefined !== $(el).data('button-text')) {
                cfg.buttonText = $(el).data('button-text');
            }

            // Update plugin's displayed field text
            if (undefined !== $(el).data('field-text')) {
                cfg.fieldText = $(el).data('field-text');
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
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineFile = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = setup.dataToCfg({
            buttonText : 'Select File',
            fieldText  : ''
        }, this);

        // Construct plugin
        return $.vine.factory({
            name    : 'vineFile',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : setup.optionsToCfg(options),
            args    : arguments
        });
    };
})(jQuery);
