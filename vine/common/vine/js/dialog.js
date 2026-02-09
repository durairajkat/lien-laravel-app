/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineDialog
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
         * Plugin's content wrapper element (usually parent element, sometimes a form).
         * ---
         * @var  element
         * @optional
         */
        we : undefined,

        /**
         * Unique ID of the compiled element.
         * ---
         * @var  int
         * @required
         */
        id : undefined,

        /**
         * Mobile status and scroll position of page when dialog was last opened.
         * ---
         * @var  bool|int
         */
        historyMobile     : false,
        historyScrollLeft : 0,
        historyScrollTop  : 0,

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
         * Compile all dialog markup, as applicable.
         * ---
         * @return  void
         */
        _compile : function() {
            this._compileDialog();
            this._compileTitle();
            this._compileButtons();
            this._compileForm();
            this._moveDialog();
        },

        /**
         * Compile dialog container markup (if applicable).
         * ---
         * @return  void
         */
        _compileDialog : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var rid;

            // Apply ID to dialog element
            if (undefined === $(pe).attr('id')) {
                rid = self.id;
                $(pe).attr('id', rid);
            // Use element's existing ID
            } else {
                rid = $(pe).attr('id');
                self.id = $(pe).attr('id');
                $(pe).attr('data-id', rid);
            }

            // Ensure dialog has applicable classes
            $(pe).removeClass('vine vine-dialog vine-strict');
            $(pe).addClass('vine vine-dialog vine-strict');

            // Place all of dialog's content inside a content class
            if ( ! $('#' + rid + ' .content').length) {
                $(pe).wrapInner('<div class="content"></div>');
            }

            // Remove responsive helpers
            $(pe).removeClass('vine-large vine-medium vine-small');

            // Desktops
            if ($('#vine-size-large').is(':visible')) {
                $(pe).addClass('vine-large');
            // Tablets
            } else if ($('#vine-size-medium').is(':visible')) {
                $(pe).addClass('vine-medium');
            // Phones
            } else {
                $(pe).addClass('vine-small');
            }
        },

        /**
         * Compile dialog title markup (if applicable).
         * ---
         * @return  void
         */
        _compileTitle : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var rid  = self.id;  // Raw primary element ID
            var title;

            // Use title specified in config
            if (cfg.title.length > 0) {
                title = cfg.title;
            // Use title specified in dialog's HTML
            } else if ($('#' + rid + ' .title').length > 0) {
                title = $('#' + rid + ' .title').text();
            // Use page title
            } else {
                title = $(document).find('title').text();
            }

            // Insert title element
            if (   cfg.hasTitle
                && ! $('#' + rid + ' .title').length
            ) {
                $(pe).prepend('<div class="title">' + title + '</div>');
                $(pe).removeClass('no-title has-title');
                $(pe).addClass('has-title');
            // Update title element
            } else if (cfg.hasTitle) {
                $('#' + rid + ' .title').text(title);
                $(pe).removeClass('no-title has-title');
                $(pe).addClass('has-title');
            // Insert a "no-title" class to dialog for style help
            } else if ( ! cfg.hasTitle && ! $('#' + rid + ' .title').length) {
                $(pe).removeClass('no-title has-title');
                $(pe).addClass('no-title');
            // Insert a "has-title" class to dialog for style help
            } else if ($('#' + rid + ' .title').length > 0) {
                $(pe).removeClass('no-title has-title');
                $(pe).addClass('has-title');
            }

            // Insert "X" (close) icon
            if (cfg.canClose && ! $('#' + rid + ' .close').length) {
                $(pe).prepend('<div class="close"></div>');
            }
        },

        /**
         * Compile dialog button markup (if applicable).
         * ---
         * @return  void
         */
        _compileButtons : function() {
            // Local vars
            var self    = this;        // Current object
            var cfg     = self.cfg;    // Current configuration
            var pe      = self.pe;     // Primary element
            var buttons = cfg.buttons; // Custom buttons
            var button  = null;        // Button details (see loop below)
            var markup  = '';          // Markup to insert into DOM

            // Always append actions element to bottom
            if ($(pe).find('.actions').length > 0) {
                $(pe).append($(pe).find('.actions'));
            // No buttons exist, stop here
            } else if ($.isEmptyObject(buttons)) {
                return;
            // Start compiling button markup if it's not already been started
            } else {
                markup += '<div class="actions">';
            }

            // Loop through and compile each button
            for (var k in buttons) {
                // This button
                button = buttons[k];

                // Compile this button's markup
                markup += '<button class="'
                        + button.cssClass + '" type="'
                        + button.type + '" data-auto-call="button-' + k + '">'
                        + button.text
                        + '</button>';
            }

            // Finish compiling button action wrapper, append to dialog
            if ( ! $(pe).find('.actions').length) {
                markup += '</div>';
                $(pe).append(markup);
            // Append buttons to existing button action wrapper
            } else {
                $(pe).find('.actions').append(markup);
            }
        },

        /**
         * Move any forms inside dialog so they wrap around all dialog content.
         * ---
         * @return  void
         */
        _compileForm : function() {
            // Local vars
            var self = this;
            var pe   = self.pe;
            var form = $(pe).find('form');
            var html = $(pe).find('form').children();

            // If dialog has a single form, wrap it around everything inside dialog
            if (1 === form.length) {
                $(form).after(html);
                $(pe).wrapInner($(form).detach());
                self.we = $(pe).find('form');
            // Dialog has no form or has more than one, wrapper element === dialog
            } else {
                self.we = pe;
            }
        },

        /**
         * Move dialog to end of document (right before </body>).
         * ---
         * @return  void
         */
        _moveDialog : function() {
            $(this.pe).appendTo(document.body);
        },

        /**
         * Activate dialog user interaction.
         * ---
         * @return  void
         */
        _activate : function() {
            // Local vars
            var self    = this;        // Current object
            var cfg     = self.cfg;    // Current configuration
            var pe      = self.pe;     // Primary element
            var rid     = self.id;     // Raw primary element ID
            var buttons = cfg.buttons; // Custom compiled buttons

            // Activate close icon's functionality
            $('#' + rid + ' .close').click(function() {
                self.close();
            });

            // Activate all call elements
            $(document).on('click', '#' + rid + ' [data-call]', function() {
                // Get all of the methods to call
                var calls = $(this).data('call').split(' ');

                // Loop through each of the caller methods
                $.each(calls, function(i) {
                    // Name of this caller method
                    var name = calls[i];

                    // Call a method inside cfg.callers
                    if ($.isFunction(cfg.callers[name])) {
                        cfg.callers[name].apply($(pe), arguments);
                    }

                    // Call native plugin method
                    if ($.isFunction(self[name])) {
                        self[name](arguments);
                    }
                });
            });


            // Activate all auto-generated button functions
            if ( ! $.isEmptyObject(buttons)) {
                // When a button that was auto-generated is clicked on
                $(document).on('click', '#' + rid + ' [data-auto-call]', function() {
                    // The button key this element belongs to
                    var call = $(this).data('auto-call').split('-');
                    var k    = call[1];

                    // Activate context aware function
                    buttons[k].onClick.apply($(pe), arguments);

                    // Stop browser navigation
                    return false;
                });
        }

            // Auto open the dialog now that everything is activated
            if (cfg.autoOpen) {
                self.open();
            }
        },

        /**
         * Opens the dialog, then executes cfg.onOpen() (as applicable).
         * ---
         * @return  bool
         */
        open : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var rid  = self.id;  // Raw primary element ID

            // Show the dialog
            $(pe).show();
            $(pe).css('visibility', 'visible');
            $('#vine-overlay').show();

            // Position the dialog
            self.historyScrollLeft = $(document).scrollLeft();
            self.historyScrollTop  = $(document).scrollTop();
            self.refresh();

            // Call "onOpen" function and trigger "dialogOpen" event
            cfg.onOpen.apply($(pe), arguments);
            $(pe).trigger('dialogOpen');
        },

        /**
         * Closes the dialog, then executes cfg.onClose() (as applicable).
         * ---
         * @return  bool
         */
        close : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var rid  = self.id;  // Raw primary element ID

            // Hide the dialog
            $(pe).hide();
            $(pe).css('visibility', 'hidden');
            $('#vine-overlay').hide();

            // Scroll back to original page position if on mobile device
            if (true === self.historyMobile) {
                window.scrollTo(self.historyScrollLeft, self.historyScrollTop);
            }

            // Call "onClose" function and trigger "dialogClose" event
            cfg.onClose.apply($(pe), arguments);
            $(pe).trigger('dialogClose');
        },

        /**
         * Refreshes the dialog, then executes cfg.onRefresh() (as applicable).
         * ---
         * @return  void
         */
        refresh : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var rid  = self.id;  // Raw primary element ID

            // Height can be forced on tablets and desktops, but not recommended
            if ( ! $(pe).hasClass('vine-small') && 'auto' !== cfg.height) {
                $(pe).find('.content').css('height', cfg.height);
            // Automatically expand height as needed based on content
            } else {
                $(pe).find('.content').css('height', 'auto');
            }

            // Get dimensions of things
            var sWidth  = $(window).width();   // Width of screen
            var sHeight = $(window).height();  // Height of screen
            var cWidth  = $(pe).outerWidth();  // Width of dialog
            var cHeight = $(pe).outerHeight(); // Height of dialog

            // (Tablets, Desktops) Position dialog
            if (true === self.isOpened() && ! $(pe).hasClass('vine-small')) {
                // Save status of viewport
                self.historyMobile = false;

                // Dialog is shorter than screen
                if ($(window).height() > $(pe).outerHeight()) {
                    $(pe).css('position', 'fixed');
                    $(pe).css('top', (sHeight - cHeight) / 2);
                // Dialog is taller than screen, position 1/10 down, scroll to top
                } else {
                    $(pe).css('position', 'absolute');
                    $(pe).css('top', (sHeight / 10));
                    $('html, body').animate({ scrollTop : 0 }, 'fast');
                }
            // (Phones) Don't position dialog, scroll to top
            } else if (true === self.isOpened()) {
                // Save status of viewport
                self.historyMobile = true;

                // Position dialog at top of page
                $(pe).css('position', 'absolute');
                $(pe).css('top', 0);
                $('html, body').animate({ scrollTop : 0 }, 'fast');
            }

            // Call "onRefresh" function and trigger "dialogRefresh" event
            cfg.onRefresh.apply($(pe), arguments);
            $(pe).trigger('dialogRefresh');
        },

        /**
         * See if the dialog is currently opened.
         * ---
         * @return  bool
         */
        isOpened : function() {
            return $(this.pe).is(':visible') ? true : false;
        },

        /**
         * See if the dialog is currently closed.
         * ---
         * @return  bool
         */
        isClosed : function() {
            return $(this.pe).is(':visible') ? false : true;
        }
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
            // Update plugin's has-title setting
            if (undefined !== $(el).data('has-title')) {
                cfg.hasTitle = $.vineQuick.toBool($(el).data('has-title'));
            }

            // Update plugin's can-close setting
            if (undefined !== $(el).data('can-close')) {
                cfg.canClose = $.vineQuick.toBool($(el).data('can-close'));
            }

            // Update plugin's auto-open setting
            if (undefined !== $(el).data('auto-open')) {
                cfg.autoOpen = $.vineQuick.toBool($(el).data('auto-open'));
            }

            // Update plugin's title
            if (undefined !== $(el).data('title')) {
                cfg.title = $(el).data('title');
            }

            // Update plugin's width
            if (undefined !== $(el).data('width')) {
                cfg.width = $(el).data('width');
            }

            // Update plugin's height
            if (undefined !== $(el).data('height')) {
                cfg.height = $(el).data('height');
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
            // The options have already been setup or are invalid, return what was input
            if (undefined === options || typeof options !== 'object') {
                return options;
            }

            // Empty or invalid button sub-object, return options with no buttons
            if (   undefined === options.buttons
                || typeof options.buttons !== 'object'
                || $.isEmptyObject(options.buttons)
            ) {
                options.buttons = {};
                return options;
            }

            // Local vars
            var data = null;
            var i    = 0;

            // Loop through each button
            for (var k in options.buttons) {
                // Stop here
                if ( ! options.buttons.hasOwnProperty(k)) {
                    break;
                }

                // Get all data from button, then delete from input
                data = options.buttons[k];
                delete options.buttons[k];

                // Start compiling this button
                options.buttons[i] = {
                    text     : 'Undefined',
                    cssClass : 'button',
                    type     : 'button',
                    onClick  : function() {}
                };

                // This button's text was defined lightly
                if (typeof k === 'string') {
                    options.buttons[i].text = k;
                }

                // This button's text was defined definitely
                if (undefined !== data.text) {
                    options.buttons[i].text = data.text;
                }

                // This button's element class name
                if (undefined !== data.cssClass) {
                    options.buttons[i].cssClass += ' ' + data.cssClass;
                }

                // This button's type
                if (undefined !== data.type) {
                    options.buttons[i].type = data.type;
                }

                // This button's onClick function was defined lightly
                if (typeof data === 'function') {
                    options.buttons[i].onClick = data;
                }

                // This button's onClick function was defined definitely
                if (typeof data.onClick === 'function') {
                    options.buttons[i].onClick = data.onClick;
                }

                // Increment
                i++;
            }

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
    $.fn.vineDialog = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = setup.dataToCfg({
            hasTitle  : true,              // Forced if element with class="title" present
            canClose  : true,              // Show "X" (close) icon in top right?
            autoOpen  : false,             // Automatically open dialog the moment it's configured?
            title     : '',                // Can be overridden by "data-title" attribute
            width     : 640,               // Ignored on mobile
            height    : 'auto',            // Ignored on mobile
            buttons   : {},                // Buttons to appear at bottom of dialog
            callers   : {},                // Dialog caller functions
            onOpen    : function(data) {}, // Called whenever dialog is opened
            onClose   : function(data) {}, // Called whenever dialog is closed
            onRefresh : function(data) {}  // Called whenever dialog is refreshed
        }, this);

        // Construct plugin
        return $.vine.factory({
            name    : 'vineDialog',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : setup.optionsToCfg(options),
            args    : arguments
        });
    };
})(jQuery);