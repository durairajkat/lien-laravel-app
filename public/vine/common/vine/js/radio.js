/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineRadio
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
            // Wrap real checkbox and stylize
            $(this.pe)
                .wrap('<div class="vine-radio-wrap" id="' + this.id + '" />')
                .before('<div class="fa vine-radio"></div>');
        },

        /**
         * Activate the fake <input type="checkbox" /> field.
         * ---
         * @return  void
         */
        _activate : function() {
            // Local vars
            var self = this;
            var id   = '#' + self.id;
            var sibs = '.vine-radio-wrap [name="' + $(self.pe).attr('name') + '"]';

            // When the real radio's value changes or is being changed
            $(self.pe).bind('change refresh', function() {
                if ($(self.pe).prop('checked')) {
                    $(id).find('.vine-radio').addClass('checked');
                } else {
                    $(id).find('.vine-radio').removeClass('checked');
                }
            }).trigger('refresh');

            // When fake checkbox is clicked
            $(id).bind('click', function() {
                $(self.pe).prop('checked', true).trigger('refresh');
                $(sibs).trigger('refresh');
            });
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineRadio = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = {};

        // Construct plugin
        return $.vine.factory({
            name    : 'vineRadio',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : options,
            args    : arguments
        });
    };
})(jQuery);