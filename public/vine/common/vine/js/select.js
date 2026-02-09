/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineSelect
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
         * Compile the markup for the fake <select> box.
         * ---
         * @return  void
         */
        _compile : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element

            // Fake dropdown text holder and styles
            var markup = '<span class="vine-text"></span>'
                       + '<span class="vine-arrow"></span>';

            // Wrap real dropdown and stylize
            $(pe).wrap('<div class="vine-select" id="' + this.id + '" />').after(markup);

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
         * Activate the fake <select> box.
         * ---
         * @return  void
         */
        _activate : function() {
            // Local vars
            var self = this;

            // When the real dropdown's value changes or is being changed
            $(self.pe).bind('change keyup refresh', function() {
                $('#' + self.id).find('.vine-text').text(
                    $(self.pe).children('option:selected').text()
                );
            }).trigger('refresh');

            // When mouseover on real dropdown
            $(self.pe).hover(
                function() { $('#' + self.id).addClass('vine-focus'); },
                function() { $('#' + self.id).removeClass('vine-focus'); }
            );

            // When the real dropdown is in focus (clicked on, tabbed into, etc.)
            $(self.pe).focus(function() {
                $('#' + self.id).addClass('vine-focus');
            });

            // When the real dropdown is blurred (tabbed out, clicked elsewhere, etc.)
            $(self.pe).blur(function() {
                $('#' + self.id).removeClass('vine-focus');
            });
        },

        /**
         * Remove all fake <select> box elements.
         * ---
         * @return  void
         */
        remove : function() {
            // Local vars
            var self = this;

            // Move <select> after <div class="vine-select">
            $(self.pe).insertAfter('#' + self.id).unbind().removeData();

            // Remove <div class="vine-select">
            $('#' + self.id).remove();
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineSelect = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = {};

        // Construct plugin
        return $.vine.factory({
            name    : 'vineSelect',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : options,
            args    : arguments
        });
    };
})(jQuery);