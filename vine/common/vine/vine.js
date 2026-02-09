/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineCalendar
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
         * An instance of Date().
         * ---
         * @var  object
         */
        dateObj : undefined,

        /**
         * An instance of Date().
         * ---
         * @var  object
         */
        appliedDate : undefined,

        /**
         * An instance of Date().
         * ---
         * @var  object
         */
        shownDate : undefined,

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
         * Compile calendar wrapper.
         * ---
         * @return  void
         */
        _compile : function() {
            // Local vars
            var self   = this;
            var id     = self.id;
            var css    = 'vine vine-strict vine-calendar ';
            var markup = '';

            // Calendar has already been compiled, stop here
            if ($(id).length > 0) {
                return;
            }

            // Desktops
            if ($('#vine-size-large').is(':visible')) {
                css += 'vine-large';
            // Tablets
            } else if ($('#vine-size-medium').is(':visible')) {
                css += 'vine-medium';
            // Phones
            } else {
                css += 'vine-small';
            }

            // Compile wrapper for calendar
            markup = '<div id="' + id + '" class="' + css + '">'
                   + '    <div class="calendar-month">'
                   + '        <div class="month-last"></div>'
                   + '        <div class="month-name"></div>'
                   + '        <div class="month-next"></div>'
                   + '    </div>'
                   + '    <div class="calendar-wrapper">'
                   + '        <table class="calendar-layout">'
                   + '            <thead>'
                   + '                <tr>'
                   + '                    <th class="day-name">Su</th>'
                   + '                    <th class="day-name">Mo</th>'
                   + '                    <th class="day-name">Tu</th>'
                   + '                    <th class="day-name">We</th>'
                   + '                    <th class="day-name">Th</th>'
                   + '                    <th class="day-name">Fr</th>'
                   + '                    <th class="day-name">Sa</th>'
                   + '                </tr>'
                   + '            </thead>'
                   + '            <tbody class="calendar-draw"></tbody>'
                   + '        </table>'
                   + '    </div>'
                   + '    <div class="calendar-actions">'
                   + '        <button '
                   + '            class="calendar-close button" '
                   + '            type="button">'
                   + '            Close'
                   + '        </button>'
                   + '    </div>'
                   + '</div>';

            // Inject into DOM (before </body>)
            $(markup).appendTo(document.body);

            // Update saved ID to always include # for easy selection later
            self.id = '#' + id;
        },

        /**
         * Activate calendar controls.
         * ---
         * @return  void
         */
        _activate : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var id   = self.id;  // Calendar element

            // Redraw the calander as the user is typing
            $(pe).bind('keyup redraw', function() {
                // Get date from field's value
                if ($(pe).val()) {
                    self.dateObj     = $.vineDate.strToDate(cfg.dateFormat, $(pe).val());
                    self.appliedDate = $.vineDate.strToDate(cfg.dateFormat, $(pe).val());
                    self.shownDate   = $.vineDate.strToDate(cfg.dateFormat, $(pe).val());
                // Get date from field's text
                } else if ($(pe).text()) {
                    self.dateObj     = $.vineDate.strToDate(cfg.dateFormat, $(pe).text());
                    self.appliedDate = $.vineDate.strToDate(cfg.dateFormat, $(pe).text());
                    self.shownDate   = $.vineDate.strToDate(cfg.dateFormat, $(pe).text());
                // Use today as the date since no date provided
                } else {
                    self.dateObj     = new Date();
                    self.appliedDate = new Date();
                    self.shownDate   = new Date();
                }

                // Redraw the calendar
                self.redraw(self.dateObj);
            // Draw calendar on DOM ready
            }).trigger('redraw');

            // Open calendar whenever attached element is clicked or focused on
            $(pe).bind('click focus', function(e) {
                // Focus only opens calendar on desktop devices
                if ('focus' === e.type && ! $(id).hasClass('vine-large')) {
                    return;
                }

                // Desktop - click, focus; Tablet - click; Phone - click
                self.open();
                e.stopPropagation();
            });

            // Close calendar whenever user tabs to next field
            $(pe).bind('keydown', function(e) {
                if (9 === e.keyCode || 9 === e.which) {
                    self.close();
                }
            });

            // Auto-close whenever element outside of calendar is focused on
            $(document).bind('click focus', function() {
                self.close();
            });

            // Auto-close behavior when element in calendar is clicked on
            $(id).click(function(e) {
                // Load previous month
                if (e.target.className.indexOf('month-last') !== -1) {
                    $(id).find('.month-last').trigger('action');
                // Load next month
                } else if (e.target.className.indexOf('month-next') !== -1) {
                    $(id).find('.month-next').trigger('action');
                // Pick this date
                } else if (e.target.className.indexOf('day-fill') !== -1) {
                    $(id).trigger('pick-day', $(e.target).text());
                // Close button was clicked (phones only)
                } else if (e.target.className.indexOf('calendar-close') !== -1) {
                    self.close();
                }

                // Prevents calendar from closing
                e.stopPropagation();
            });

            // Load the previous month
            $(id).find('.month-last').bind('action', function() {
                if (0 === self.dateObj.getMonth()) {
                    self.dateObj = new Date(self.dateObj.getFullYear() - 1, 11, 1);
                    self.redraw(self.dateObj);
                } else {
                    self.dateObj = new Date(self.dateObj.getFullYear(), self.dateObj.getMonth() - 1, 1);
                    self.redraw(self.dateObj);
                }
            });

            // Load the next month
            $(id).find('.month-next').bind('action', function() {
                if (11 === self.dateObj.getMonth()) {
                    self.dateObj = new Date(self.dateObj.getFullYear() + 1, 0, 1);
                    self.redraw(self.dateObj);
                } else {
                    self.dateObj = new Date(self.dateObj.getFullYear(), self.dateObj.getMonth() + 1, 1);
                    self.redraw(self.dateObj);
                }
            });

            // Set a new date
            $(id).bind('pick-day', function(e, day) {
                day = parseFloat(day);
                self.dateObj     = new Date(self.shownDate.getFullYear(), self.shownDate.getMonth(), day);
                self.appliedDate = new Date(self.shownDate.getFullYear(), self.shownDate.getMonth(), day);
                self.shownDate   = new Date(self.shownDate.getFullYear(), self.shownDate.getMonth(), day);
                $(pe).val($.vineDate.dateToStr(cfg.dateFormat, self.shownDate));
                self.close();
                self.redraw(self.dateObj);
            });
        },

        /**
         * Open the calendar.
         * ---
         * @return  void
         */
        open : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Parent element
            var id   = self.id;  // Calendar element

            // Close any other calendars
            $('.vine-calendar').each(function() {
                // ID of this calendar's parent element
                var tid = $(this).attr('id');

                // Close this calendar
                if (('#' + tid) !== id) {
                    $('[data-id="' + tid + '"]').vineCalendar('close');
                }
            });

            // Always redraw the calendar before opening it
            $(pe).trigger('redraw');

            // Save scroll position before calendar is opened
            self.historyScrollLeft = $(document).scrollLeft();
            self.historyScrollTop  = $(document).scrollTop();

            // Show calendar; position calendar; trigger events
            $(id).show();
            self.refresh();
            $(pe).trigger('calendarOpen');
        },

        /**
         * Close the calendar.
         * ---
         * @return  void
         */
        close : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Parent element
            var id   = self.id;  // Calendar element

            // Close the calendar
            if ($(id).is(':visible')) {
                // Hide the calendar
                $(id).hide();
                $('#vine-overlay').hide();

                // Scroll back to original page position if on mobile device
                if (true === self.historyMobile) {
                    window.scrollTo(self.historyScrollLeft, self.historyScrollTop);
                }

                // Trigger events
                $(pe).trigger('calendarClose');
            }
        },

        /**
         * Redraw the calendar.
         * ---
         * @param   object  Instance of Date().
         * @return  string
         */
        redraw : function(dateObj) {
            // Local vars
            var self   = this;     // Current object
            var cfg    = self.cfg; // Current configuration
            var pe     = self.pe;  // Parent element
            var id     = self.id;  // Calendar element
            var now    = new Date();
            var iDate  = new Date(dateObj.getFullYear(), dateObj.getMonth(), 1);
            var year   = dateObj.getFullYear();
            var month  = dateObj.getMonth();
            var day    = self.appliedDate.getDate();
            var markup = '';
            var i;

            // Save the date being shown
            self.shownDate = new Date(dateObj.getFullYear(), dateObj.getMonth(), 1);

            // Fill empty days at the beginning of the month
            for (i = 0; i < iDate.getDay(); i++) {
                markup += '<td class="day-empty"></td>';
            }

            // Loop through and compile the calendar
            for (i = 0; i < 31; i++) {
                // Comple this day in the loop
                if (iDate.getDate() > i) {
                    // Start compiling markup for this cell
                    markup += '<td class="day-cell">';

                    // This is the current date, highlight well
                    if (   i + 1 === now.getDate()
                        && now.getMonth() === month
                        && now.getFullYear() === year
                    ) {
                        markup += '<div class="day-now day-fill">'
                                + iDate.getDate()
                                + '</div>';
                    // Highlight this day
                    } else if (i + 1 === day
                        && self.appliedDate.getMonth()    === month
                        && self.appliedDate.getFullYear() === year
                    ) {
                        markup += '<div class="day-set day-fill">'
                                + iDate.getDate()
                                + '</div>';
                    // Don't highlight this day
                    } else {
                        markup += '<div class="day-fill">'
                                + (i + 1)
                                + '</div>';
                    }

                    // Finish compiling markup for this cell
                    markup += '</td>';
                }

                // Increment date +1 day
                iDate.setDate(iDate.getDate() + 1);
            }

            // Update calendar's markup
            $(id).find('.month-name').text($.vineDate.getMonthName(month) + ' ' + year);
            $(id).find('.calendar-draw').html(markup);
            $(id).find('.calendar-draw').children('td').vineAutoWrap(7, '<tr>');
            $(pe).trigger('calendarRedraw');
            self.refresh();
        },

        /**
         * Refresh the position of the calendar.
         * ---
         * @return  void
         */
        refresh : function() {
            // Local vars
            var self    = this;                     // Current object
            var cfg     = self.cfg;                 // Current configuration
            var pe      = self.pe;                  // Parent element
            var id      = self.id;                  // Calendar element
            var sWidth  = $(window).width();        // Width of screen
            var sHeight = $(window).height();       // Height of screen
            var pWidth  = $(pe).outerWidth();       // Parent element's width
            var pHeight = $(pe).outerHeight();      // Parent element's height
            var pOff    = $(pe).offset();           // Parent element's offsets
            var cWidth  = $(id).outerWidth();       // Calendar's width
            var cHeight = $(id).outerHeight();      // Calendar's height
            var sTop    = $(document).scrollTop();  // Scroll position from top
            var sLeft   = $(document).scrollLeft(); // Scroll position from left

            // Calendar isn't visible, don't refresh, stop here
            if ( ! $(id).is(':visible')) {
                return $(self.pe);
            }

            // Use full-screen calendar on phones
            if ($(id).hasClass('vine-small')) {
                // Save status of viewport
                self.historyMobile = true;

                // Position dialog at top of page
                $('html, body').animate({ scrollTop : 0 }, 'fast');
                $('#vine-overlay').show();
                $(id).css({
                    top  : 0,
                    left : 0
                });
            // Use regular calendar on tablets and desktops
            } else {
                // Save status of viewport
                self.historyMobile = false;

                // Position calendar above parent element
                if (pOff.top + pHeight + cHeight >= sHeight) {
                    $('#vine-overlay').hide();
                    $(id).css({
                        top  : (pOff.top - cHeight - 3) + 'px',
                        left : pOff.left + 'px'
                    });
                // Position calendar below parent element
                } else {
                    $('#vine-overlay').hide();
                    $(id).css({
                        top  : (pOff.top + pHeight + 3) + 'px',
                        left : pOff.left + 'px'
                    });
                }
            }

            // Refresh calendar
            $(pe).trigger('calendarRefresh');
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineCalendar = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = {
            autoOpen   : false,
            dateFormat : 'Y-m-d'
        };

        // Construct plugin
        return $.vine.factory({
            name    : 'vineCalendar',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : options,
            args    : arguments
        });
    };
})(jQuery); 
 
/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineCheckbox
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
                .wrap('<div class="vine-checkbox-wrap" id="' + this.id + '" />')
                .before('<div class="fa vine-checkbox"></div>');
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

            // When the real checkbox's value changes or is being changed
            $(self.pe).bind('change refresh', function() {
                if ($(self.pe).prop('checked')) {
                    $(id).find('.vine-checkbox').addClass('checked');
                } else {
                    $(id).find('.vine-checkbox').removeClass('checked');
                }
            }).trigger('refresh');

            // When fake checkbox is NOT disabled
            if (self.pe.attr('disabled') !== 'disabled') {
                // When fake checkbox is clicked
                $(id).bind('click', function() {
                    if ($(self.pe).prop('checked')) {
                        $(self.pe).prop('checked', false).trigger('refresh');
                    } else {
                        $(self.pe).prop('checked', true).trigger('refresh');
                    }
                });
            // When fake checkbox is disabled
            } else {
                $(id).find('.vine-checkbox').addClass('disabled');
            }
        }
    };

    /**
     * Plugin constructor.
     * ---
     * @param   mixed
     * @return  object
     */
    $.fn.vineCheckbox = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = {};

        // Construct plugin
        return $.vine.factory({
            name    : 'vineCheckbox',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : options,
            args    : arguments
        });
    };
})(jQuery);
 
 
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
 
/*!
 * jQuery Form Plugin
 * version: 3.51.0-2014.06.20
 * Requires jQuery v1.5 or later
 * Copyright (c) 2014 M. Alsup
 * Examples and documentation at: http://malsup.com/jquery/form/
 * Project repository: https://github.com/malsup/form
 * Dual licensed under the MIT and GPL licenses.
 * https://github.com/malsup/form#copyright-and-license
 */
/*global ActiveXObject */

// AMD support
(function (factory) {
    "use strict";
    if (typeof define === 'function' && define.amd) {
        // using AMD; register as anon module
        define(['jquery'], factory);
    } else {
        // no AMD; invoke directly
        factory( (typeof(jQuery) != 'undefined') ? jQuery : window.Zepto );
    }
}

(function($) {
"use strict";

/*
    Usage Note:
    -----------
    Do not use both ajaxSubmit and ajaxForm on the same form.  These
    functions are mutually exclusive.  Use ajaxSubmit if you want
    to bind your own submit handler to the form.  For example,

    $(document).ready(function() {
        $('#myForm').on('submit', function(e) {
            e.preventDefault(); // <-- important
            $(this).ajaxSubmit({
                target: '#output'
            });
        });
    });

    Use ajaxForm when you want the plugin to manage all the event binding
    for you.  For example,

    $(document).ready(function() {
        $('#myForm').ajaxForm({
            target: '#output'
        });
    });

    You can also use ajaxForm with delegation (requires jQuery v1.7+), so the
    form does not have to exist when you invoke ajaxForm:

    $('#myForm').ajaxForm({
        delegation: true,
        target: '#output'
    });

    When using ajaxForm, the ajaxSubmit function will be invoked for you
    at the appropriate time.
*/

/**
 * Feature detection
 */
var feature = {};
feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
feature.formdata = window.FormData !== undefined;

var hasProp = !!$.fn.prop;

// attr2 uses prop when it can but checks the return type for
// an expected string.  this accounts for the case where a form 
// contains inputs with names like "action" or "method"; in those
// cases "prop" returns the element
$.fn.attr2 = function() {
    if ( ! hasProp ) {
        return this.attr.apply(this, arguments);
    }
    var val = this.prop.apply(this, arguments);
    if ( ( val && val.jquery ) || typeof val === 'string' ) {
        return val;
    }
    return this.attr.apply(this, arguments);
};

/**
 * ajaxSubmit() provides a mechanism for immediately submitting
 * an HTML form using AJAX.
 */
$.fn.ajaxSubmit = function(options) {
    /*jshint scripturl:true */

    // fast fail if nothing selected (http://dev.jquery.com/ticket/2752)
    if (!this.length) {
        log('ajaxSubmit: skipping submit process - no element selected');
        return this;
    }

    var method, action, url, $form = this;

    if (typeof options == 'function') {
        options = { success: options };
    }
    else if ( options === undefined ) {
        options = {};
    }

    method = options.type || this.attr2('method');
    action = options.url  || this.attr2('action');

    url = (typeof action === 'string') ? $.trim(action) : '';
    url = url || window.location.href || '';
    if (url) {
        // clean url (don't include hash vaue)
        url = (url.match(/^([^#]+)/)||[])[1];
    }

    options = $.extend(true, {
        url:  url,
        success: $.ajaxSettings.success,
        type: method || $.ajaxSettings.type,
        iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
    }, options);

    // hook for manipulating the form data before it is extracted;
    // convenient for use with rich editors like tinyMCE or FCKEditor
    var veto = {};
    this.trigger('form-pre-serialize', [this, options, veto]);
    if (veto.veto) {
        log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
        return this;
    }

    // provide opportunity to alter form data before it is serialized
    if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
        log('ajaxSubmit: submit aborted via beforeSerialize callback');
        return this;
    }

    var traditional = options.traditional;
    if ( traditional === undefined ) {
        traditional = $.ajaxSettings.traditional;
    }

    var elements = [];
    var qx, a = this.formToArray(options.semantic, elements);
    if (options.data) {
        options.extraData = options.data;
        qx = $.param(options.data, traditional);
    }

    // give pre-submit callback an opportunity to abort the submit
    if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
        log('ajaxSubmit: submit aborted via beforeSubmit callback');
        return this;
    }

    // fire vetoable 'validate' event
    this.trigger('form-submit-validate', [a, this, options, veto]);
    if (veto.veto) {
        log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
        return this;
    }

    var q = $.param(a, traditional);
    if (qx) {
        q = ( q ? (q + '&' + qx) : qx );
    }
    if (options.type.toUpperCase() == 'GET') {
        options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
        options.data = null;  // data is null for 'get'
    }
    else {
        options.data = q; // data is the query string for 'post'
    }

    var callbacks = [];
    if (options.resetForm) {
        callbacks.push(function() { $form.resetForm(); });
    }
    if (options.clearForm) {
        callbacks.push(function() { $form.clearForm(options.includeHidden); });
    }

    // perform a load on the target only if dataType is not provided
    if (!options.dataType && options.target) {
        var oldSuccess = options.success || function(){};
        callbacks.push(function(data) {
            var fn = options.replaceTarget ? 'replaceWith' : 'html';
            $(options.target)[fn](data).each(oldSuccess, arguments);
        });
    }
    else if (options.success) {
        callbacks.push(options.success);
    }

    options.success = function(data, status, xhr) { // jQuery 1.4+ passes xhr as 3rd arg
        var context = options.context || this ;    // jQuery 1.4+ supports scope context
        for (var i=0, max=callbacks.length; i < max; i++) {
            callbacks[i].apply(context, [data, status, xhr || $form, $form]);
        }
    };

    if (options.error) {
        var oldError = options.error;
        options.error = function(xhr, status, error) {
            var context = options.context || this;
            oldError.apply(context, [xhr, status, error, $form]);
        };
    }

     if (options.complete) {
        var oldComplete = options.complete;
        options.complete = function(xhr, status) {
            var context = options.context || this;
            oldComplete.apply(context, [xhr, status, $form]);
        };
    }

    // are there files to upload?

    // [value] (issue #113), also see comment:
    // https://github.com/malsup/form/commit/588306aedba1de01388032d5f42a60159eea9228#commitcomment-2180219
    var fileInputs = $('input[type=file]:enabled', this).filter(function() { return $(this).val() !== ''; });

    var hasFileInputs = fileInputs.length > 0;
    var mp = 'multipart/form-data';
    var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

    var fileAPI = feature.fileapi && feature.formdata;
    log("fileAPI :" + fileAPI);
    var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;

    var jqxhr;

    // options.iframe allows user to force iframe mode
    // 06-NOV-09: now defaulting to iframe mode if file input is detected
    if (options.iframe !== false && (options.iframe || shouldUseFrame)) {
        // hack to fix Safari hang (thanks to Tim Molendijk for this)
        // see:  http://groups.google.com/group/jquery-dev/browse_thread/thread/36395b7ab510dd5d
        if (options.closeKeepAlive) {
            $.get(options.closeKeepAlive, function() {
                jqxhr = fileUploadIframe(a);
            });
        }
        else {
            jqxhr = fileUploadIframe(a);
        }
    }
    else if ((hasFileInputs || multipart) && fileAPI) {
        jqxhr = fileUploadXhr(a);
    }
    else {
        jqxhr = $.ajax(options);
    }

    $form.removeData('jqxhr').data('jqxhr', jqxhr);

    // clear element array
    for (var k=0; k < elements.length; k++) {
        elements[k] = null;
    }

    // fire 'notify' event
    this.trigger('form-submit-notify', [this, options]);
    return this;

    // utility fn for deep serialization
    function deepSerialize(extraData){
        var serialized = $.param(extraData, options.traditional).split('&');
        var len = serialized.length;
        var result = [];
        var i, part;
        for (i=0; i < len; i++) {
            // #252; undo param space replacement
            serialized[i] = serialized[i].replace(/\+/g,' ');
            part = serialized[i].split('=');
            // #278; use array instead of object storage, favoring array serializations
            result.push([decodeURIComponent(part[0]), decodeURIComponent(part[1])]);
        }
        return result;
    }

     // XMLHttpRequest Level 2 file uploads (big hat tip to francois2metz)
    function fileUploadXhr(a) {
        var formdata = new FormData();

        for (var i=0; i < a.length; i++) {
            formdata.append(a[i].name, a[i].value);
        }

        if (options.extraData) {
            var serializedData = deepSerialize(options.extraData);
            for (i=0; i < serializedData.length; i++) {
                if (serializedData[i]) {
                    formdata.append(serializedData[i][0], serializedData[i][1]);
                }
            }
        }

        options.data = null;

        var s = $.extend(true, {}, $.ajaxSettings, options, {
            contentType: false,
            processData: false,
            cache: false,
            type: method || 'POST'
        });

        if (options.uploadProgress) {
            // workaround because jqXHR does not expose upload property
            s.xhr = function() {
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function(event) {
                        var percent = 0;
                        var position = event.loaded || event.position; /*event.position is deprecated*/
                        var total = event.total;
                        if (event.lengthComputable) {
                            percent = Math.ceil(position / total * 100);
                        }
                        options.uploadProgress(event, position, total, percent);
                    }, false);
                }
                return xhr;
            };
        }

        s.data = null;
        var beforeSend = s.beforeSend;
        s.beforeSend = function(xhr, o) {
            //Send FormData() provided by user
            if (options.formData) {
                o.data = options.formData;
            }
            else {
                o.data = formdata;
            }
            if(beforeSend) {
                beforeSend.call(this, xhr, o);
            }
        };
        return $.ajax(s);
    }

    // private function for handling file uploads (hat tip to YAHOO!)
    function fileUploadIframe(a) {
        var form = $form[0], el, i, s, g, id, $io, io, xhr, sub, n, timedOut, timeoutHandle;
        var deferred = $.Deferred();

        // #341
        deferred.abort = function(status) {
            xhr.abort(status);
        };

        if (a) {
            // ensure that every serialized input is still enabled
            for (i=0; i < elements.length; i++) {
                el = $(elements[i]);
                if ( hasProp ) {
                    el.prop('disabled', false);
                }
                else {
                    el.removeAttr('disabled');
                }
            }
        }

        s = $.extend(true, {}, $.ajaxSettings, options);
        s.context = s.context || s;
        id = 'jqFormIO' + (new Date().getTime());
        if (s.iframeTarget) {
            $io = $(s.iframeTarget);
            n = $io.attr2('name');
            if (!n) {
                $io.attr2('name', id);
            }
            else {
                id = n;
            }
        }
        else {
            $io = $('<iframe name="' + id + '" src="'+ s.iframeSrc +'" />');
            $io.css({ position: 'absolute', top: '-1000px', left: '-1000px' });
        }
        io = $io[0];


        xhr = { // mock object
            aborted: 0,
            responseText: null,
            responseXML: null,
            status: 0,
            statusText: 'n/a',
            getAllResponseHeaders: function() {},
            getResponseHeader: function() {},
            setRequestHeader: function() {},
            abort: function(status) {
                var e = (status === 'timeout' ? 'timeout' : 'aborted');
                log('aborting upload... ' + e);
                this.aborted = 1;

                try { // #214, #257
                    if (io.contentWindow.document.execCommand) {
                        io.contentWindow.document.execCommand('Stop');
                    }
                }
                catch(ignore) {}

                $io.attr('src', s.iframeSrc); // abort op in progress
                xhr.error = e;
                if (s.error) {
                    s.error.call(s.context, xhr, e, status);
                }
                if (g) {
                    $.event.trigger("ajaxError", [xhr, s, e]);
                }
                if (s.complete) {
                    s.complete.call(s.context, xhr, e);
                }
            }
        };

        g = s.global;
        // trigger ajax global events so that activity/block indicators work like normal
        if (g && 0 === $.active++) {
            $.event.trigger("ajaxStart");
        }
        if (g) {
            $.event.trigger("ajaxSend", [xhr, s]);
        }

        if (s.beforeSend && s.beforeSend.call(s.context, xhr, s) === false) {
            if (s.global) {
                $.active--;
            }
            deferred.reject();
            return deferred;
        }
        if (xhr.aborted) {
            deferred.reject();
            return deferred;
        }

        // add submitting element to data if we know it
        sub = form.clk;
        if (sub) {
            n = sub.name;
            if (n && !sub.disabled) {
                s.extraData = s.extraData || {};
                s.extraData[n] = sub.value;
                if (sub.type == "image") {
                    s.extraData[n+'.x'] = form.clk_x;
                    s.extraData[n+'.y'] = form.clk_y;
                }
            }
        }

        var CLIENT_TIMEOUT_ABORT = 1;
        var SERVER_ABORT = 2;
                
        function getDoc(frame) {
            /* it looks like contentWindow or contentDocument do not
             * carry the protocol property in ie8, when running under ssl
             * frame.document is the only valid response document, since
             * the protocol is know but not on the other two objects. strange?
             * "Same origin policy" http://en.wikipedia.org/wiki/Same_origin_policy
             */
            
            var doc = null;
            
            // IE8 cascading access check
            try {
                if (frame.contentWindow) {
                    doc = frame.contentWindow.document;
                }
            } catch(err) {
                // IE8 access denied under ssl & missing protocol
                log('cannot get iframe.contentWindow document: ' + err);
            }

            if (doc) { // successful getting content
                return doc;
            }

            try { // simply checking may throw in ie8 under ssl or mismatched protocol
                doc = frame.contentDocument ? frame.contentDocument : frame.document;
            } catch(err) {
                // last attempt
                log('cannot get iframe.contentDocument: ' + err);
                doc = frame.document;
            }
            return doc;
        }

        // Rails CSRF hack (thanks to Yvan Barthelemy)
        var csrf_token = $('meta[name=csrf-token]').attr('content');
        var csrf_param = $('meta[name=csrf-param]').attr('content');
        if (csrf_param && csrf_token) {
            s.extraData = s.extraData || {};
            s.extraData[csrf_param] = csrf_token;
        }

        // take a breath so that pending repaints get some cpu time before the upload starts
        function doSubmit() {
            // make sure form attrs are set
            var t = $form.attr2('target'), 
                a = $form.attr2('action'), 
                mp = 'multipart/form-data',
                et = $form.attr('enctype') || $form.attr('encoding') || mp;

            // update form attrs in IE friendly way
            form.setAttribute('target',id);
            if (!method || /post/i.test(method) ) {
                form.setAttribute('method', 'POST');
            }
            if (a != s.url) {
                form.setAttribute('action', s.url);
            }

            // ie borks in some cases when setting encoding
            if (! s.skipEncodingOverride && (!method || /post/i.test(method))) {
                $form.attr({
                    encoding: 'multipart/form-data',
                    enctype:  'multipart/form-data'
                });
            }

            // support timout
            if (s.timeout) {
                timeoutHandle = setTimeout(function() { timedOut = true; cb(CLIENT_TIMEOUT_ABORT); }, s.timeout);
            }

            // look for server aborts
            function checkState() {
                try {
                    var state = getDoc(io).readyState;
                    log('state = ' + state);
                    if (state && state.toLowerCase() == 'uninitialized') {
                        setTimeout(checkState,50);
                    }
                }
                catch(e) {
                    log('Server abort: ' , e, ' (', e.name, ')');
                    cb(SERVER_ABORT);
                    if (timeoutHandle) {
                        clearTimeout(timeoutHandle);
                    }
                    timeoutHandle = undefined;
                }
            }

            // add "extra" data to form if provided in options
            var extraInputs = [];
            try {
                if (s.extraData) {
                    for (var n in s.extraData) {
                        if (s.extraData.hasOwnProperty(n)) {
                           // if using the $.param format that allows for multiple values with the same name
                           if($.isPlainObject(s.extraData[n]) && s.extraData[n].hasOwnProperty('name') && s.extraData[n].hasOwnProperty('value')) {
                               extraInputs.push(
                               $('<input type="hidden" name="'+s.extraData[n].name+'">').val(s.extraData[n].value)
                                   .appendTo(form)[0]);
                           } else {
                               extraInputs.push(
                               $('<input type="hidden" name="'+n+'">').val(s.extraData[n])
                                   .appendTo(form)[0]);
                           }
                        }
                    }
                }

                if (!s.iframeTarget) {
                    // add iframe to doc and submit the form
                    $io.appendTo('body');
                }
                if (io.attachEvent) {
                    io.attachEvent('onload', cb);
                }
                else {
                    io.addEventListener('load', cb, false);
                }
                setTimeout(checkState,15);

                try {
                    form.submit();
                } catch(err) {
                    // just in case form has element with name/id of 'submit'
                    var submitFn = document.createElement('form').submit;
                    submitFn.apply(form);
                }
            }
            finally {
                // reset attrs and remove "extra" input elements
                form.setAttribute('action',a);
                form.setAttribute('enctype', et); // #380
                if(t) {
                    form.setAttribute('target', t);
                } else {
                    $form.removeAttr('target');
                }
                $(extraInputs).remove();
            }
        }

        if (s.forceSync) {
            doSubmit();
        }
        else {
            setTimeout(doSubmit, 10); // this lets dom updates render
        }

        var data, doc, domCheckCount = 50, callbackProcessed;

        function cb(e) {
            if (xhr.aborted || callbackProcessed) {
                return;
            }
            
            doc = getDoc(io);
            if(!doc) {
                log('cannot access response document');
                e = SERVER_ABORT;
            }
            if (e === CLIENT_TIMEOUT_ABORT && xhr) {
                xhr.abort('timeout');
                deferred.reject(xhr, 'timeout');
                return;
            }
            else if (e == SERVER_ABORT && xhr) {
                xhr.abort('server abort');
                deferred.reject(xhr, 'error', 'server abort');
                return;
            }

            if (!doc || doc.location.href == s.iframeSrc) {
                // response not received yet
                if (!timedOut) {
                    return;
                }
            }
            if (io.detachEvent) {
                io.detachEvent('onload', cb);
            }
            else {
                io.removeEventListener('load', cb, false);
            }

            var status = 'success', errMsg;
            try {
                if (timedOut) {
                    throw 'timeout';
                }

                var isXml = s.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
                log('isXml='+isXml);
                if (!isXml && window.opera && (doc.body === null || !doc.body.innerHTML)) {
                    if (--domCheckCount) {
                        // in some browsers (Opera) the iframe DOM is not always traversable when
                        // the onload callback fires, so we loop a bit to accommodate
                        log('requeing onLoad callback, DOM not available');
                        setTimeout(cb, 250);
                        return;
                    }
                    // let this fall through because server response could be an empty document
                    //log('Could not access iframe DOM after mutiple tries.');
                    //throw 'DOMException: not available';
                }

                //log('response detected');
                var docRoot = doc.body ? doc.body : doc.documentElement;
                xhr.responseText = docRoot ? docRoot.innerHTML : null;
                xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
                if (isXml) {
                    s.dataType = 'xml';
                }
                xhr.getResponseHeader = function(header){
                    var headers = {'content-type': s.dataType};
                    return headers[header.toLowerCase()];
                };
                // support for XHR 'status' & 'statusText' emulation :
                if (docRoot) {
                    xhr.status = Number( docRoot.getAttribute('status') ) || xhr.status;
                    xhr.statusText = docRoot.getAttribute('statusText') || xhr.statusText;
                }

                var dt = (s.dataType || '').toLowerCase();
                var scr = /(json|script|text)/.test(dt);
                if (scr || s.textarea) {
                    // see if user embedded response in textarea
                    var ta = doc.getElementsByTagName('textarea')[0];
                    if (ta) {
                        xhr.responseText = ta.value;
                        // support for XHR 'status' & 'statusText' emulation :
                        xhr.status = Number( ta.getAttribute('status') ) || xhr.status;
                        xhr.statusText = ta.getAttribute('statusText') || xhr.statusText;
                    }
                    else if (scr) {
                        // account for browsers injecting pre around json response
                        var pre = doc.getElementsByTagName('pre')[0];
                        var b = doc.getElementsByTagName('body')[0];
                        if (pre) {
                            xhr.responseText = pre.textContent ? pre.textContent : pre.innerText;
                        }
                        else if (b) {
                            xhr.responseText = b.textContent ? b.textContent : b.innerText;
                        }
                    }
                }
                else if (dt == 'xml' && !xhr.responseXML && xhr.responseText) {
                    xhr.responseXML = toXml(xhr.responseText);
                }

                try {
                    data = httpData(xhr, dt, s);
                }
                catch (err) {
                    status = 'parsererror';
                    xhr.error = errMsg = (err || status);
                }
            }
            catch (err) {
                log('error caught: ',err);
                status = 'error';
                xhr.error = errMsg = (err || status);
            }

            if (xhr.aborted) {
                log('upload aborted');
                status = null;
            }

            if (xhr.status) { // we've set xhr.status
                status = (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) ? 'success' : 'error';
            }

            // ordering of these callbacks/triggers is odd, but that's how $.ajax does it
            if (status === 'success') {
                if (s.success) {
                    s.success.call(s.context, data, 'success', xhr);
                }
                deferred.resolve(xhr.responseText, 'success', xhr);
                if (g) {
                    $.event.trigger("ajaxSuccess", [xhr, s]);
                }
            }
            else if (status) {
                if (errMsg === undefined) {
                    errMsg = xhr.statusText;
                }
                if (s.error) {
                    s.error.call(s.context, xhr, status, errMsg);
                }
                deferred.reject(xhr, 'error', errMsg);
                if (g) {
                    $.event.trigger("ajaxError", [xhr, s, errMsg]);
                }
            }

            if (g) {
                $.event.trigger("ajaxComplete", [xhr, s]);
            }

            if (g && ! --$.active) {
                $.event.trigger("ajaxStop");
            }

            if (s.complete) {
                s.complete.call(s.context, xhr, status);
            }

            callbackProcessed = true;
            if (s.timeout) {
                clearTimeout(timeoutHandle);
            }

            // clean up
            setTimeout(function() {
                if (!s.iframeTarget) {
                    $io.remove();
                }
                else { //adding else to clean up existing iframe response.
                    $io.attr('src', s.iframeSrc);
                }
                xhr.responseXML = null;
            }, 100);
        }

        var toXml = $.parseXML || function(s, doc) { // use parseXML if available (jQuery 1.5+)
            if (window.ActiveXObject) {
                doc = new ActiveXObject('Microsoft.XMLDOM');
                doc.async = 'false';
                doc.loadXML(s);
            }
            else {
                doc = (new DOMParser()).parseFromString(s, 'text/xml');
            }
            return (doc && doc.documentElement && doc.documentElement.nodeName != 'parsererror') ? doc : null;
        };
        var parseJSON = $.parseJSON || function(s) {
            /*jslint evil:true */
            return window['eval']('(' + s + ')');
        };

        var httpData = function( xhr, type, s ) { // mostly lifted from jq1.4.4

            var ct = xhr.getResponseHeader('content-type') || '',
                xml = type === 'xml' || !type && ct.indexOf('xml') >= 0,
                data = xml ? xhr.responseXML : xhr.responseText;

            if (xml && data.documentElement.nodeName === 'parsererror') {
                if ($.error) {
                    $.error('parsererror');
                }
            }
            if (s && s.dataFilter) {
                data = s.dataFilter(data, type);
            }
            if (typeof data === 'string') {
                if (type === 'json' || !type && ct.indexOf('json') >= 0) {
                    data = parseJSON(data);
                } else if (type === "script" || !type && ct.indexOf("javascript") >= 0) {
                    $.globalEval(data);
                }
            }
            return data;
        };

        return deferred;
    }
};

/**
 * ajaxForm() provides a mechanism for fully automating form submission.
 *
 * The advantages of using this method instead of ajaxSubmit() are:
 *
 * 1: This method will include coordinates for <input type="image" /> elements (if the element
 *    is used to submit the form).
 * 2. This method will include the submit element's name/value data (for the element that was
 *    used to submit the form).
 * 3. This method binds the submit() method to the form for you.
 *
 * The options argument for ajaxForm works exactly as it does for ajaxSubmit.  ajaxForm merely
 * passes the options argument along after properly binding events for submit elements and
 * the form itself.
 */
$.fn.ajaxForm = function(options) {
    options = options || {};
    options.delegation = options.delegation && $.isFunction($.fn.on);

    // in jQuery 1.3+ we can fix mistakes with the ready state
    if (!options.delegation && this.length === 0) {
        var o = { s: this.selector, c: this.context };
        if (!$.isReady && o.s) {
            log('DOM not ready, queuing ajaxForm');
            $(function() {
                $(o.s,o.c).ajaxForm(options);
            });
            return this;
        }
        // is your DOM ready?  http://docs.jquery.com/Tutorials:Introducing_$(document).ready()
        log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
        return this;
    }

    if ( options.delegation ) {
        $(document)
            .off('submit.form-plugin', this.selector, doAjaxSubmit)
            .off('click.form-plugin', this.selector, captureSubmittingElement)
            .on('submit.form-plugin', this.selector, options, doAjaxSubmit)
            .on('click.form-plugin', this.selector, options, captureSubmittingElement);
        return this;
    }

    return this.ajaxFormUnbind()
        .bind('submit.form-plugin', options, doAjaxSubmit)
        .bind('click.form-plugin', options, captureSubmittingElement);
};

// private event handlers
function doAjaxSubmit(e) {
    /*jshint validthis:true */
    var options = e.data;
    if (!e.isDefaultPrevented()) { // if event has been canceled, don't proceed
        e.preventDefault();
        $(e.target).ajaxSubmit(options); // #365
    }
}

function captureSubmittingElement(e) {
    /*jshint validthis:true */
    var target = e.target;
    var $el = $(target);
    if (!($el.is("[type=submit],[type=image]"))) {
        // is this a child element of the submit el?  (ex: a span within a button)
        var t = $el.closest('[type=submit]');
        if (t.length === 0) {
            return;
        }
        target = t[0];
    }
    var form = this;
    form.clk = target;
    if (target.type == 'image') {
        if (e.offsetX !== undefined) {
            form.clk_x = e.offsetX;
            form.clk_y = e.offsetY;
        } else if (typeof $.fn.offset == 'function') {
            var offset = $el.offset();
            form.clk_x = e.pageX - offset.left;
            form.clk_y = e.pageY - offset.top;
        } else {
            form.clk_x = e.pageX - target.offsetLeft;
            form.clk_y = e.pageY - target.offsetTop;
        }
    }
    // clear form vars
    setTimeout(function() { form.clk = form.clk_x = form.clk_y = null; }, 100);
}


// ajaxFormUnbind unbinds the event handlers that were bound by ajaxForm
$.fn.ajaxFormUnbind = function() {
    return this.unbind('submit.form-plugin click.form-plugin');
};

/**
 * formToArray() gathers form element data into an array of objects that can
 * be passed to any of the following ajax functions: $.get, $.post, or load.
 * Each object in the array has both a 'name' and 'value' property.  An example of
 * an array for a simple login form might be:
 *
 * [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
 *
 * It is this array that is passed to pre-submit callback functions provided to the
 * ajaxSubmit() and ajaxForm() methods.
 */
$.fn.formToArray = function(semantic, elements) {
    var a = [];
    if (this.length === 0) {
        return a;
    }

    var form = this[0];
    var formId = this.attr('id');
    var els = semantic ? form.getElementsByTagName('*') : form.elements;
    var els2;

    if (els && !/MSIE [678]/.test(navigator.userAgent)) { // #390
        els = $(els).get();  // convert to standard array
    }

    // #386; account for inputs outside the form which use the 'form' attribute
    if ( formId ) {
        els2 = $(':input[form="' + formId + '"]').get(); // hat tip @thet
        if ( els2.length ) {
            els = (els || []).concat(els2);
        }
    }

    if (!els || !els.length) {
        return a;
    }

    var i,j,n,v,el,max,jmax;
    for(i=0, max=els.length; i < max; i++) {
        el = els[i];
        n = el.name;
        if (!n || el.disabled) {
            continue;
        }

        if (semantic && form.clk && el.type == "image") {
            // handle image inputs on the fly when semantic == true
            if(form.clk == el) {
                a.push({name: n, value: $(el).val(), type: el.type });
                a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
            }
            continue;
        }

        v = $.fieldValue(el, true);
        if (v && v.constructor == Array) {
            if (elements) {
                elements.push(el);
            }
            for(j=0, jmax=v.length; j < jmax; j++) {
                a.push({name: n, value: v[j]});
            }
        }
        else if (feature.fileapi && el.type == 'file') {
            if (elements) {
                elements.push(el);
            }
            var files = el.files;
            if (files.length) {
                for (j=0; j < files.length; j++) {
                    a.push({name: n, value: files[j], type: el.type});
                }
            }
            else {
                // #180
                a.push({ name: n, value: '', type: el.type });
            }
        }
        else if (v !== null && typeof v != 'undefined') {
            if (elements) {
                elements.push(el);
            }
            a.push({name: n, value: v, type: el.type, required: el.required});
        }
    }

    if (!semantic && form.clk) {
        // input type=='image' are not found in elements array! handle it here
        var $input = $(form.clk), input = $input[0];
        n = input.name;
        if (n && !input.disabled && input.type == 'image') {
            a.push({name: n, value: $input.val()});
            a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
        }
    }
    return a;
};

/**
 * Serializes form data into a 'submittable' string. This method will return a string
 * in the format: name1=value1&amp;name2=value2
 */
$.fn.formSerialize = function(semantic) {
    //hand off to jQuery.param for proper encoding
    return $.param(this.formToArray(semantic));
};

/**
 * Serializes all field elements in the jQuery object into a query string.
 * This method will return a string in the format: name1=value1&amp;name2=value2
 */
$.fn.fieldSerialize = function(successful) {
    var a = [];
    this.each(function() {
        var n = this.name;
        if (!n) {
            return;
        }
        var v = $.fieldValue(this, successful);
        if (v && v.constructor == Array) {
            for (var i=0,max=v.length; i < max; i++) {
                a.push({name: n, value: v[i]});
            }
        }
        else if (v !== null && typeof v != 'undefined') {
            a.push({name: this.name, value: v});
        }
    });
    //hand off to jQuery.param for proper encoding
    return $.param(a);
};

/**
 * Returns the value(s) of the element in the matched set.  For example, consider the following form:
 *
 *  <form><fieldset>
 *      <input name="A" type="text" />
 *      <input name="A" type="text" />
 *      <input name="B" type="checkbox" value="B1" />
 *      <input name="B" type="checkbox" value="B2"/>
 *      <input name="C" type="radio" value="C1" />
 *      <input name="C" type="radio" value="C2" />
 *  </fieldset></form>
 *
 *  var v = $('input[type=text]').fieldValue();
 *  // if no values are entered into the text inputs
 *  v == ['','']
 *  // if values entered into the text inputs are 'foo' and 'bar'
 *  v == ['foo','bar']
 *
 *  var v = $('input[type=checkbox]').fieldValue();
 *  // if neither checkbox is checked
 *  v === undefined
 *  // if both checkboxes are checked
 *  v == ['B1', 'B2']
 *
 *  var v = $('input[type=radio]').fieldValue();
 *  // if neither radio is checked
 *  v === undefined
 *  // if first radio is checked
 *  v == ['C1']
 *
 * The successful argument controls whether or not the field element must be 'successful'
 * (per http://www.w3.org/TR/html4/interact/forms.html#successful-controls).
 * The default value of the successful argument is true.  If this value is false the value(s)
 * for each element is returned.
 *
 * Note: This method *always* returns an array.  If no valid value can be determined the
 *    array will be empty, otherwise it will contain one or more values.
 */
$.fn.fieldValue = function(successful) {
    for (var val=[], i=0, max=this.length; i < max; i++) {
        var el = this[i];
        var v = $.fieldValue(el, successful);
        if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length)) {
            continue;
        }
        if (v.constructor == Array) {
            $.merge(val, v);
        }
        else {
            val.push(v);
        }
    }
    return val;
};

/**
 * Returns the value of the field element.
 */
$.fieldValue = function(el, successful) {
    var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
    if (successful === undefined) {
        successful = true;
    }

    if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
        (t == 'checkbox' || t == 'radio') && !el.checked ||
        (t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
        tag == 'select' && el.selectedIndex == -1)) {
            return null;
    }

    if (tag == 'select') {
        var index = el.selectedIndex;
        if (index < 0) {
            return null;
        }
        var a = [], ops = el.options;
        var one = (t == 'select-one');
        var max = (one ? index+1 : ops.length);
        for(var i=(one ? index : 0); i < max; i++) {
            var op = ops[i];
            if (op.selected) {
                var v = op.value;
                if (!v) { // extra pain for IE...
                    v = (op.attributes && op.attributes.value && !(op.attributes.value.specified)) ? op.text : op.value;
                }
                if (one) {
                    return v;
                }
                a.push(v);
            }
        }
        return a;
    }
    return $(el).val();
};

/**
 * Clears the form data.  Takes the following actions on the form's input fields:
 *  - input text fields will have their 'value' property set to the empty string
 *  - select elements will have their 'selectedIndex' property set to -1
 *  - checkbox and radio inputs will have their 'checked' property set to false
 *  - inputs of type submit, button, reset, and hidden will *not* be effected
 *  - button elements will *not* be effected
 */
$.fn.clearForm = function(includeHidden) {
    return this.each(function() {
        $('input,select,textarea', this).clearFields(includeHidden);
    });
};

/**
 * Clears the selected form elements.
 */
$.fn.clearFields = $.fn.clearInputs = function(includeHidden) {
    var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i; // 'hidden' is not in this list
    return this.each(function() {
        var t = this.type, tag = this.tagName.toLowerCase();
        if (re.test(t) || tag == 'textarea') {
            this.value = '';
        }
        else if (t == 'checkbox' || t == 'radio') {
            this.checked = false;
        }
        else if (tag == 'select') {
            this.selectedIndex = -1;
        }
        else if (t == "file") {
            if (/MSIE/.test(navigator.userAgent)) {
                $(this).replaceWith($(this).clone(true));
            } else {
                $(this).val('');
            }
        }
        else if (includeHidden) {
            // includeHidden can be the value true, or it can be a selector string
            // indicating a special test; for example:
            //  $('#myForm').clearForm('.special:hidden')
            // the above would clean hidden inputs that have the class of 'special'
            if ( (includeHidden === true && /hidden/.test(t)) ||
                 (typeof includeHidden == 'string' && $(this).is(includeHidden)) ) {
                this.value = '';
            }
        }
    });
};

/**
 * Resets the form data.  Causes all form elements to be reset to their original value.
 */
$.fn.resetForm = function() {
    return this.each(function() {
        // guard against an input with the name of 'reset'
        // note that IE reports the reset function as an 'object'
        if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {
            this.reset();
        }
    });
};

/**
 * Enables or disables any matching elements.
 */
$.fn.enable = function(b) {
    if (b === undefined) {
        b = true;
    }
    return this.each(function() {
        this.disabled = !b;
    });
};

/**
 * Checks/unchecks any matching checkboxes or radio buttons and
 * selects/deselects and matching option elements.
 */
$.fn.selected = function(select) {
    if (select === undefined) {
        select = true;
    }
    return this.each(function() {
        var t = this.type;
        if (t == 'checkbox' || t == 'radio') {
            this.checked = select;
        }
        else if (this.tagName.toLowerCase() == 'option') {
            var $sel = $(this).parent('select');
            if (select && $sel[0] && $sel[0].type == 'select-one') {
                // deselect all other options
                $sel.find('option').selected(false);
            }
            this.selected = select;
        }
    });
};

// expose debug var
$.fn.ajaxSubmit.debug = false;

// helper fn for console logging
function log() {
    if (!$.fn.ajaxSubmit.debug) {
        return;
    }
    var msg = '[jquery.form] ' + Array.prototype.join.call(arguments,'');
    if (window.console && window.console.log) {
        window.console.log(msg);
    }
    else if (window.opera && window.opera.postError) {
        window.opera.postError(msg);
    }
}

}));
 
 
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
 
/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vine
 */
;(function($) { 'use strict';
    /**
     * Initialize the VineUI framework when DOM is ready.
     */
    $(document).ready(function() {
        bootstrap.start();
    });

    /**
     * Refresh the VineUI framework whenever window is resized.
     */
    $(window).resize(function() {
        bootstrap.refresh();
    });

    /**
     * VineUI bootstrap.
     */
    var bootstrap = {
        /**
         * Activate VineUI framework.
         * ---
         * @return  void
         */
        start : function() {
            // Make sure design is responsive when flagged to do so
            if ($('.vine-responsive').length > 0 && ! $('meta[name="viewport"]').length) {
                $('head').prepend('<meta name="viewport" content="initial-scale=1">');
            }

            // Compile screensize helpers
            $('<div id="vine-size-small"></div>').appendTo(document.body);
            $('<div id="vine-size-medium"></div>').appendTo(document.body);
            $('<div id="vine-size-large"></div>').appendTo(document.body);
            $('<div id="vine-overlay"></div>').appendTo(document.body);

            // Activate individual widgets and initialize responsiveness
            this.startCalendars();
            this.startDialogs();
            this.startFiles();
            this.startForms();
            this.startSelects();
            this.startCheckboxes();
            this.startRadios();
            this.startTables();
            this.refresh();
        },

        /**
         * Refresh all of activated elements of the VineUI framework.
         * ---
         * @return  void
         */
        refresh : function() {
            // Remove responsive helpers
            $('.vine, #vine-overlay').removeClass('vine-large vine-medium vine-small');

            // Desktops
            if ($('#vine-size-large').is(':visible')) {
                $('.vine, #vine-overlay').addClass('vine-large');
            // Tablets
            } else if ($('#vine-size-medium').is(':visible')) {
                $('.vine, #vine-overlay').addClass('vine-medium');
            // Phones
            } else {
                $('.vine, #vine-overlay').addClass('vine-small');
            }

            // Refresh individual widgets
            this.refreshCalendars();
            this.refreshDialogs();
            this.refreshFiles();
            this.refreshForms();
            this.refreshSelects();
            this.refreshCheckboxes();
            this.refreshRadios();
            this.refreshTables();
        },

        /**
         * Activate calendar widgets.
         * ---
         * @return  void
         */
        startCalendars : function() {
            $('[data-widget="calendar"]').vineCalendar();
        },

        /**
         * Activate dialog widgets.
         * ---
         * @return  void
         */
        startDialogs : function() {
            // Automatically register elements with this attribute as dialogs
            $('[data-widget="dialog"]').vineDialog();

            // Whenever an element is clicked instructing a dialog to open
            $('[data-dialog-open]').click(function() {
                // The ID of the dialog to try opening
                var id = $(this).data('dialog-open');

                // Element found, open dialog
                if ('dialog' === $('#' + id).data('widget')) {
                    $('#' + id).vineDialog('open');
                }
            });

            // Whenever an element is clicked instructing a dialog to close
            $('[data-dialog-close]').click(function() {
                // The ID of the dialog to try closing
                var id = $(this).data('dialog-close');

                // Element found, close dialog
                if ('dialog' === $('#' + id).data('widget')) {
                    $('#' + id).vineDialog('close');
                }
            });

            // Whenever the overlay is clicked
            $(document).on('click', '#vine-overlay', function() {
                $('.vine.dialog:visible').vineDialog('close');
            });
        },

        /**
         * Activate form file input widgets.
         * ---
         * @return  void
         */
        startFiles : function() {
            $('.vine input[type="file"]').vineFile();
        },

        /**
         * Activate calendar widgets.
         * ---
         * @return  void
         */
        startForms : function() {
            $('[data-widget="form"]').vineForm();
        },

        /**
         * Activate <select> dropdown widgets.
         * ---
         * @return  void
         */
        startSelects : function() {
            $('.vine select').vineSelect();
        },

        /**
         * Activate <input type="checkbox"> widgets.
         * ---
         * @return  void
         */
        startCheckboxes : function() {
            $('.vine input[type="checkbox"]').vineCheckbox();
        },

        /**
         * Activate <input type="radio"> widgets.
         * ---
         * @return  void
         */
        startRadios : function() {
            $('.vine input[type="radio"]').vineRadio();
        },

        /**
         * Activate table widgets.
         * ---
         * @return  void
         */
        startTables : function() {
            $('[data-widget="table"]').each(function() {
                $(this).vineTable();
            });
        },

        /**
         * Activate calendar widgets.
         * ---
         * @return  void
         */
        refreshCalendars : function() {
            // Refresh each calendar widget
            $('[data-widget="calendar"]').each(function() {
                $(this).vineCalendar('refresh');
            });
        },

        /**
         * Refresh dialog widgets.
         * ---
         * @return  void
         */
        refreshDialogs : function() {
            // Refresh each visible dialog
            $('.vine-dialog:visible').each(function() {
                $(this).vineDialog('refresh');
            });
        },

        /**
         * Refresh form file input fields.
         * ---
         * @return  void
         */
        refreshFiles : function() { },

        /**
         * Refresh forms.
         * ---
         * @return  void
         */
        refreshForms : function() { },

        /**
         * Refresh <select> dropdown widgets.
         * ---
         * @return  void
         */
        refreshSelects : function() { },

        /**
         * Refresh <input type="checkbox"> widgets.
         * ---
         * @return  void
         */
        refreshCheckboxes : function() { },

        /**
         * Refresh <input type="radio"> widgets.
         * ---
         * @return  void
         */
        refreshRadios : function() { },

        /**
         * Refresh table widgets.
         * ---
         * @return  void
         */
        refreshTables : function() {
            // Refresh each table
            $('[data-widget="table"]').each(function() {
                $(this).vineTable('refresh');
            });
        }
    };
})(jQuery); 
 
/**
 * @licence  Copyright 2016 Tell Konkle. All rights reserved.
 * @author   Tell Konkle <tellkonkle@gmail.com>
 * @title    vineTable
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
         * <table style=""> attribute before modifications.
         * ---
         * @var  string
         */
        peStyles : '',

        /**
         * Markup for sorting arrows.
         * ---
         * @var  string
         */
        up     : '<span class="vine-arrow-up"></span>',
        down   : '<span class="vine-arrow-down"></span>',
        arrows : '<span class="vine-arrows">'
               + '    <span class="vine-arrow-up"></span>'
               + '    <span class="vine-arrow-down"></span>'
               + '</span>',

        /**
         * Class constructor.
         * ---
         * @required
         */
        _start : function() {
            this._compile();
            this._compileTools();
            this._makeSortable();
            this._makeEmptyMsg();
        },

        /**
         * Compile all markup, as applicable.
         * ---
         * @return  void
         */
        _compile : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element

            // Add a class to all column names in the header (for responsiveness)
            $(pe).find('thead th, thead td').addClass('vine-th');

            // Wrap table with a <div> tag
            $(pe).wrap('<div class="vine-table-wrap" id="' + self.id + '" />');

            // Save wrapper element
            self.we = $(pe).parent();

            // Save <table style=""> attribute before it gets modified
            self.peStyles = undefined !== $(pe).attr('style') ? $(pe).attr('style') : '';

            // Desktops
            if ($('#vine-size-large').is(':visible')) {
                $(self.we).addClass('vine vine-table vine-large');
            // Tablets
            } else if ($('#vine-size-medium').is(':visible')) {
                $(self.we).addClass('vine vine-table vine-medium');
            // Phones
            } else {
                $(self.we).addClass('vine vine-table vine-small');
            }

            // Make table responsive
            if (cfg.responsive) {
                $(self.we).addClass('responsive');
            }

            // Duplicate table's margins on wrapper
            $(self.we).css({
                'margin-top'    : $(pe).css('margin-top'),
                'margin-right'  : $(pe).css('margin-right'),
                'margin-bottom' : $(pe).css('margin-bottom'),
                'margin-left'   : $(pe).css('margin-left')
            });

            // Forcibily remove table's margins
            $(pe).attr('style', 'margin: 0 !important;' + self.peStyles);
        },

        /**
         * Compile extra functionality, including sorting, search, and pagination tools.
         * ---
         * @return  void
         */
        _compileTools : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element

            // Compile desktop, laptop, tablet sorting
            self._compileStandardSort();

            // Compile mobile sorting
            if (cfg.responsive) {
                self._compileMobileSort();
            }

            // Compile pagination functionality
            if (cfg.pages) {
                self._compilePagination();
            }

            // Compile search and title functionality
            if (cfg.search || cfg.title.length > 0) {
                self._compileSearch();
            }
        },

        /**
         * Compile markup for mobile sorting functionality.
         * ---
         * @return  void
         */
        _compileMobileSort : function() {
            // Local vars
            var self     = this;                       // Current object
            var cfg      = self.cfg;                   // Current configuration
            var pe       = self.pe;                    // Primary element
            var we       = self.we;                    // Wrapper element
            var options  = '';                         // Markup for <option> tags
            var markup   = '';                         // Markup for injecting <option>s
            var sortable = $(pe).hasClass('sortable'); // Start search for sort details

            // Compile <select> dropdown
            $(pe).find('.vine-th').each(function() {
                if (sortable || $(this).hasClass('sortable')) {
                    options += '<option value="' + ($(this).index() + 1) + '">'
                             + $.trim($(this).text())
                             + '</option>';
                }
            });

            // None of the columns were given sort permissions, stop here
            if ( ! options.length) {
                return;
            }

            // Compile markup for sorting <select> dropdown
            markup = '<div class="vine-sort-wrap">'
                   + '    <div class="vine-sort-label">'
                   + '        <div class="vine-sort-label-inner">Sort By</div>'
                   + '    </div>'
                   + '    <div class="vine-sort-field">'
                   + '        <select class="vine-sort">'
                   + '            ' + options
                   + '        </select>'
                   + '    </div>'
                   + '</div>';

            // Inject markup before table element
            $(pe).before(markup);

            // Apply vineSelect plugin to <select> dropdown
            $(we).find('.vine-sort').vineSelect();
        },

        /**
         * Compile markup for standard sorting functionality.
         * ---
         * @return  void
         */
        _compileStandardSort : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element

            // Loop through each column header and add sorting arrows as applicable
            $(pe).find('.vine-th').each(function() {
                // Make this column searchable by index
                $(this).attr('data-index', $(this).index() + 1);

                // This table doesn't need sorting arrows since it gets no styling
                if ( ! $(pe).hasClass('stylize')) {
                    return;
                }

                // This is a sortable column
                if ($(pe).hasClass('sortable') || $(this).hasClass('sortable')) {
                    $(this).append(self.arrows).wrapInner('<div class="vine-name"></div>');
                }
            });
        },

        /**
         * Compile markup for search functionality.
         * ---
         * @return  void
         */
        _compileSearch : function() {
            // Local vars
            var self   = this;     // Current object
            var cfg    = self.cfg; // Current configuration
            var pe     = self.pe;  // Primary element
            var we     = self.we;  // Wrapper element
            var markup = '';       // Markup to be compiled

            // Helps with CSS styling
            if (cfg.search) {
                $(we).addClass('has-search');
            }

            // Helps with CSS styling
            if (cfg.title.length > 0) {
                $(we).addClass('has-title');
            }

            // Start compiling markup
            markup += '<div class="vine-search-wrap">';

            // Compile table title and search field
            if (cfg.search && cfg.title.length > 0) {
                // Compile search field
                markup += '<div class="vine-search">'
                        + '    <input '
                        + '        class="vine-search-field" '
                        + '        type="text" '
                        + '        placeholder="Search..." '
                        + '    />'
                        + '    <span class="fa fa-search vine-search-icon"></span>'
                        + '</div>';

                // Compile title
                markup += '<div class="vine-title col-sm-8 text-right">'
                        + cfg.title
                        + '</div>';
            // Compile just the search field
            } else if (cfg.search) {
                // Compile search field
                markup += '<div class="vine-search">'
                        + '    <input '
                        + '        class="vine-search-field" '
                        + '        type="text" '
                        + '        placeholder="Search..." '
                        + '    />'
                        + '    <span class="fa fa-search vine-search-icon"></span>'
                        + '</div>';
            // Compile just the table title
            } else {
                // Compile title
                markup += '<div class="vine-title">'
                        + cfg.title
                        + '</div>';
            }

            // Finish compiling markup
            markup += '</div>';

            // Place search tool and/or title above the table
            $(we).prepend(markup);

            // Whenever search field changes
            $(we).find('.vine-search-field').bind('change keyup', function() {
                // The search term
                var search = $(this).val();

                // Loop through all table rows and disable ones that do not match search
                $(pe).find('tbody tr').each(function() {
                    // This row doesn't contain search term
                    if (-1 === $(this).text().indexOf(search)) {
                        $(this).addClass('disabled');
                        $(this).removeClass('enabled');
                    // This row does contain search term
                    } else {
                        $(this).removeClass('disabled');
                        $(this).addClass('enabled');
                    }
                });

                // Re-render page
                self._renderPages(1);
            }).keyup();
        },

        /**
         * Compile markup for pagination.
         * ---
         * @return  void
         */
        _compilePagination : function() {
            // Local vars
            var self    = this;     // Current object
            var cfg     = self.cfg; // Current configuration
            var pe      = self.pe;  // Primary element
            var we      = self.we;  // Wrapper element

            // Helps with CSS styling
            $(we).addClass('has-pages');

            // Place pagination below the table
            $(we).append(
                '<div class="vine-pages">'
              + '    <div class="vine-page-options"></div>'
              + '    <ul class="vine-page-list"></ul>'
              + '</div>'
            );

            // Re-render whenever a page is clicked
            $(document).on('click', '#' + this.id + ' .vine-page', function() {
                $(we).find('.vine-pages .active').removeClass('active');
                $(this).addClass('active');
                self._renderPages(parseInt($(this).text()));
            });

            // Generate pagination links and show applicable rows
            self._compileOptions();
            self._renderPages(1);
        },

        /**
         * Compile page options.
         * ---
         * @return  void
         */
        _compileOptions : function() {
            // Local vars
            var self    = this;             // Current object
            var cfg     = self.cfg;         // Current configuration
            var pe      = self.pe;          // Primary element
            var we      = self.we;          // Wrapper element
            var options = cfg.pagesOptions; // Page option array
            var markup  = '';               // Markup to be compiled

            // Start compiling options
            markup += '<select class="vine-page-option">';

            // Loop through and compile each option
            for (var i = 0; i < options.length; i++) {
                // This is the default page option
                if (options[i] == cfg.pagesDefault) {
                    markup += '<option value="' + options[i] + '" selected="selected">'
                            + options[i] + ' ' + cfg.pagesText
                            + '</option>';
                // Non-default page option
                } else {
                    markup += '<option value="' + options[i] + '">'
                            + options[i] + ' ' + cfg.pagesText
                            + '</option>';
                }
            }

            // Finish compiling options
            markup += '</select>';

            // Place options next to pagination
            $(we).find('.vine-page-options').html(markup);

            // Activate <select> dropdown
            $(we).find('.vine-page-option').vineSelect();

            // Re-render pages whenever page option changes
            $(we).find('.vine-page-option').change(function() {
                self._renderPages(1);
            });
        },

        /**
         * Redraw the table's pagination.
         * ---
         * @return  void
         */
        _renderPages : function(now) {
            // Local vars
            var self   = this;                   // Current object
            var cfg    = self.cfg;               // Current configuration
            var pe     = self.pe;                // Primary element
            var we     = self.we;                // Wrapper element
            var info   = self._getPageInfo(now); // All of the pagination info
            var markup = '';                     // Markup to be compiled
            var page   = 0;                      // Pointer page
            var pages  = info.pages.length;

            // Loop through and compile each page link
            for (var i = 0; i < pages; i++) {
                // This page
                page = info.pages[i];

                // This is a page span
                if ('...' === page) {
                    markup += '<li class="vine-fill">...</li>';
                // This is the active page
                } else if (page === now) {
                    markup += '<li class="vine-page active"><i>' + page + '</i></li>';
                // This is a regular page link
                } else {
                    markup += '<li class="vine-page"><i>' + page + '</i></li>';
                }
            }

            // Place markup in pagination wrapper and render applicable pages
            if (cfg.pages) {
                $(we).find('.vine-page-list').html(markup);
                $(pe).find('tbody tr').removeClass('first');
                $(pe).find('tbody tr:not(.disabled)').show();
                $(pe).find('tbody tr.disabled').hide();
                $(pe).find('tbody tr:not(.disabled):lt(' +  info.start + ')').hide();
                $(pe).find('tbody tr:not(.disabled):gt(' +  info.end + ')').hide();
                $(pe).find('tbody tr:visible').first().addClass('first');
                $(window).resize();
            // Pagination has been disabled, render everything
            } else {
                $(pe).find('tbody tr').removeClass('first');
                $(pe).find('tbody tr:not(.disabled)').show();
                $(pe).find('tbody tr.disabled').hide();
                $(pe).find('tbody tr:visible').first().addClass('first');
                $(window).resize();
            }
        },

        /**
         * Compile an object containing all of the info needed to paginate the table.
         * Below is an example of the result:
         * ---
         * object = {
         *     display : 'Page 1 of 18',
         *     active  : 1,
         *     total   : 18,
         *     prev    : 1,
         *     next    : 2,
         *     pages   : [1,2,3,4,5]
         * };
         * ---
         * @return  object
         */
        _getPageInfo : function(now) {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element
            var each = parseInt($(we).find('.vine-page-option').val());

            // Calculations
            var active    = now;
            var start     = (active * each) - each;
            var end       = (active * each) - 1;
            var rows      = $(pe).find('tbody tr:not(.disabled)').length;
            var pages     = rows > 1 ? Math.ceil(rows / each) : 1;
            var prev      = 1 === active ? 1 : (active - 1);
            var next      = pages === active || (active + 1) > pages ? pages : (active + 1);
            var pageSpan  = self._getPageSpan(active, pages, next);
            var pageFirst = self._showFirstPage(pageSpan);
            var pageLast  = self._showLastPage(pageSpan, pages);

            // Compile an object containing all pagination info
            var info = {
                'display'    : 'Page ' + active + ' of ' + pages,
                'start'      : start,
                'end'        : end,
                'active'     : active,
                'total'      : pages,
                'prev'       : prev,
                'next'       : next,
                'pagesSpan'  : pageSpan,
                'pagesFirst' : pageFirst,
                'pagesLast'  : pageLast,
                'pages'      : self._unique(pageFirst.concat(pageSpan).concat(pageLast)),
            };

            // (object) All of the pagination info
            return info;
        },

        /**
         * Compile an array containing the page numbers surrounding the active page.
         * ---
         * @param   int    The active page number.
         * @param   int    The last/total page number.
         * @param   int    The next page number.
         * @return  array  A simple array of page numbers.
         */
        _getPageSpan : function(active, last, next) {
            // Local vars
            var self  = this;     // Current object
            var cfg   = self.cfg; // Current configuration
            var pe    = self.pe;  // Primary element
            var we    = self.we;  // Wrapper element
            var pages = [];       // Pages to compile
            var start, i;         // Other variables

            // When active page is the first page
            if (1 === active) {
                // There's only one page, stop here
                if (next === active) {
                    pages.push(1);
                    return pages;
                }

                // Compile an array of page numbers
                for (i = 0; i < cfg.pagesSpan; i++) {
                    // Link span is greater than page count, stop loop once it's reached
                    if (last === i) {
                        break;
                    }

                    // Add this page number (count started at 0, so add 1)
                    pages.push(i + 1);
                }

                // (array) Applicable page numbers
                return pages;
            }

            // When active page is the last page
            if (last === active) {
                // The page to start showing links at
                start = last - cfg.pagesSpan;

                // Link span is greater than page count, so start at first page
                if (start < 1) {
                    start = 0;
                }

                // Compile an array of page numbers (count starts at 0, so always add 1)
                for (i = start; i < last; i++) {
                    pages.push(i + 1);
                }

                // (array) Applicable page numbers
                return pages;
            }

            // Calculate the start page
            start = active - cfg.pagesSpan;

            // Span is greater than page count, so start at first page
            if (start < 1) {
                start = 0;
            }

            // Compile page numbers BEFORE active page (count starts at 0, always add 1)
            for (i = start; i < active; i++) {
                pages.push(i + 1);
            }

            // Compile page numbers AFTER active page
            for (i = (active + 1); i < (active + cfg.pagesSpan); i++) {
                // Stop loop if page reaches the last page for the result set
                if (i >= (last + 1)) {
                    break;
                }

                // Add this page number
                pages.push(i);
            }

            // (array)
            return pages;
        },

        /**
         * Show the first page in the pagination list? An array is returned in one of the
         * following formats:
         * ---
         * array [1, '...'];
         *
         * OR:
         *
         * array [1];
         * ---
         * @param   array  A page span array generated by _getPageSpan().
         * @return  array  Empty if first page number is already being shown.
         */
        _showFirstPage : function(pages) {
            // The first page number shown in the page list
            var firstShown = pages[0];

            // Show first page, plus a ... text span
            if (firstShown >= 3) {
                return [1, '...'];
            // Show first page with no ... text span
            } else if (firstShown === 2) {
                return [1];
            // First page is already being shown
            } else {
                return [];
            }
        },

        /**
         * Show the last page in the pagination list? An array is returned in one of the
         * following automated formats:
         * ---
         * array ['...', 13];
         *
         * OR:
         *
         * array [13];
         * ---
         * @param   array  A page span array generated by _getPageSpan().
         * @param   int    The last page in the result set.
         * @return  array  Empty if last page number is already being shown, or array.
         */
        _showLastPage : function(pages, last) {
            // The last page number shown in the page list
            var total     = pages.length;
            var lastShown = pages[total - 1];

            // Show last page, preceded a ... text span
            if (lastShown <= (last - 2)) {
                return ['...', last];
            // Show last page with no ... text span
            } else if (lastShown === (last - 1)) {
                return [last];
            // Last page is already being shown
            } else {
                return [];
            }
        },

        /**
         * Remove duplicate values from an array.
         * ---
         * @param   array
         * @return  array
         */
        _unique : function(array) {
            var a = array.concat();

            for (var i = 0; i < a.length; i++) {
                for (var j= i+1; j < a.length; j++) {
                    if (a[i] === a[j]) {
                        a.splice(j--, 1);
                    }
                }
            }

            return a;
        },

        /**
         * Make the tabular data sortable.
         * ---
         * @return  void
         */
        _makeSortable : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element

            // When a column header is clicked
            $(document).on('click', '#' + self.id + ' .vine-th', function(e) {
                // Local vars
                var th = this;

                // This column should not be sortable
                if ( ! $(pe).hasClass('sortable') && ! $(th).hasClass('sortable')) {
                    return;
                }

                // Sort descending
                if ($(th).data('sort') === 'asc') {
                    $(th).data('sort', 'desc');
                // Sort ascending
                } else if ($(this).data('sort') === 'desc') {
                    $(th).data('sort', 'asc');
                // First sort, sort decending
                } else {
                    $(th).data('sort', 'desc');
                }

                // Add correct sorting arrow if applicable
                if ($(pe).hasClass('stylize')) {
                    // Remove old arrows
                    $(pe).find('.vine-arrows, .vine-arrow-up, .vine-arrow-down').remove();

                    // Add new arrows to every column but this one
                    $(th).siblings().find('.vine-name').append(self.arrows);

                    // Add ascending arrow to this column
                    if ($(th).data('sort') === 'asc') {
                        $(th).children().append(self.up);
                    // Add descending arrow to this column
                    } else {
                        $(th).children().append(self.down);
                    }
                }

                // Sort the table
                self.sort($(th).index() + 1, th);
            });

            // Whenever mobile sort <select> dropdown changes
            $(document).on('change', '#' + self.id + '  select.vine-sort', function() {
                // Remove dynamically created columns
                $(pe).find('.column-name').remove();

                // Always use ASC sorting on mobile (maybe improve in future)
                $('#' + self.id + ' .vine-th[data-index="' + $(this).val() + '"]')
                    .data('sort', 'asc')
                    .click();

                // Trigger event listed above this one
                self.refresh();
            });
        },

        /**
         * Make an "empty message" if table has no rows.
         * ---
         * @return  void
         */
        _makeEmptyMsg : function() {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element

            // No empty message needs compiled
            if ($(pe).find('tbody tr').length) {
                return;
            }

            // Column count, compiled message
            var span = $(pe).find('thead tr:first-child th').length;
            var msg  = '<tr><td colspan="' + span + '" class="empty-text">'
                     + cfg.emptyText
                     + '</td></tr>';

            // Insert compiled message into table's body
            $(pe).find('tbody').html(msg);
        },

        /**
         * Get all of the column names of the table and add them to a simple array.
         * ---
         * @return  array
         * @see     _getCol()
         */
        _getCols : function() {
            // Local vars
            var self = this;
            var el   = $(self.pe).find('th');
            var cols = [];

            // Loop through each column in table header
            $(el).each(function(i) {
                cols[i] = $(this).text();
            });

            // (array)
            return cols;
        },

        /**
         * Safely get a column name from a column name array.
         * ---
         * @param   int     The column key to get.
         * @param   array   The column name array (@see _getCols()).
         * @return  string  A column name or empty string.
         * @see     _getCols()
         */
        _getCol : function(key, cols) {
            // Column exists
            if (typeof cols[key] !== 'undefined') {
                return cols[key];
            // Colspan was likely used on table headers
            } else {
                return '';
            }
        },

        /**
         * Sort the table by a specified column.
         * ---
         * @param   int   The column's DOM index.
         * @param   elem  The column's primary DOM element (table head <th>).
         * @return  void
         */
        sort : function(i, th) {
            // Local vars
            var self = this;     // Current object
            var cfg  = self.cfg; // Current configuration
            var pe   = self.pe;  // Primary element
            var we   = self.we;  // Wrapper element

            // Re-build the table
            $(pe).find('tbody').html(
                $(pe).find('tbody tr').sort(function(a, b) {
                    // A and B are table rows; grab the correct column in each row
                    var ca = $(a).find('td:nth-child(' + i + ')').text();
                    var cb = $(b).find('td:nth-child(' + i + ')').text();

                    // Row A's column is an alpha numeric string
                    if (isNaN(ca)) {
                        ca = ca.toLowerCase();
                    // Row A's column is a numeric string
                    } else {
                        ca = parseFloat(ca);
                    }

                    // Row B's column is an alpha numeric string
                    if (isNaN(cb)) {
                        cb = cb.toLowerCase();
                    // Row B's column is a numeric string
                    } else {
                        cb = parseFloat(cb);
                    }

                    // Swap vars to reverse sorting order
                    if ($(th).data('sort') === 'asc') {
                        cb = [ca, ca = cb][0];
                    }

                    // Row A's column is less than row B's column
                    if (ca < cb) {
                        return -1;
                    // Row A's column is greater than row B's column
                    } else if (ca > cb) {
                        return 1;
                    // Both column values are equal
                    } else {
                        return 0;
                    }
                })
            );
        },

        /**
         * Refreshes the table, then executes cfg.onRefresh() (as applicable).
         * ---
         * @return  void
         */
        refresh : function() {
            // Local vars
            var self = this;                          // Current object
            var cfg  = self.cfg;                      // Current configuration
            var pe   = self.pe;                       // Primary element
            var we   = self.we;                       // Wrapper element
            var cols = [];                            // Column names
            var ins  = [];                            // Column indexes
            var t    = $(pe).find('.vine-th').length; // Total number of columns
            var i    = 0;                             // Row pointer

            // Call "onRefresh" function and trigger "tableRefresh" event
            cfg.onRefresh.apply($(pe), arguments);
            $(pe).trigger('tableRefresh');

            // Remove old column names
            $(pe).find('.column-name').remove();

            // Get column names
            i = 0; $(pe).find('.vine-th').each(function() {
                i++;
                cols.push($.trim($(this).text()));
                ins.push($(this).index() + 1);
            });

            // Responsive functionality
            if ($(we).hasClass('vine-small') && $(we).hasClass('responsive')) {
                // Loop through each "cell-turned-row"
                $(pe).find('tbody td').each(function() {
                    $(this).before(
                        '<td class="column-name" data-index="'
                        + ($(this).index() + 1)
                        + '" />'
                    );
                });

                // Loop through each cell
                i = 0; $(pe).find('td.column-name').each(function() {
                    // Add column name
                    $(this).text(cols[i]);
                    $(this).attr('data-index', ins[i]);

                    // Increment
                    i++;

                    // Reset increment
                    if (i % t === 0) {
                        i = 0;
                    }
                });
            }
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
            // Update plugin's title setting
            if (undefined !== $(el).data('title')) {
                cfg.title = $(el).data('title');
            }

            // Update plugin's responsive setting
            if (undefined !== $(el).data('responsive')) {
                cfg.responsive = $.vineQuick.toBool($(el).data('responsive'));
            }

            // Update plugin's search setting
            if (undefined !== $(el).data('search')) {
                cfg.search = $.vineQuick.toBool($(el).data('search'));
            }

            // Update plugin's pagination setting
            if (undefined !== $(el).data('pages')) {
                cfg.pages = $.vineQuick.toBool($(el).data('pages'));
            }

            // Update plugin's pagination options
            if (undefined !== $(el).data('pages-options')) {
                cfg.pagesOptions = $(el).data('pages-options').replace(' ', '').split(',');
            }

            // Update plugin's default per-page value
            if (undefined !== $(el).data('pages-default')) {
                cfg.pagesDefault = parseInt($(el).data('pages-default'));
            }

            // Update plugin's default page span
            if (undefined !== $(el).data('pages-span')) {
                cfg.pagesSpan = parseInt($(el).data('pages-span'));
            }

            // Update plugin's default page text
            if (undefined !== $(el).data('pages-text')) {
                cfg.pagesText = $(el).data('pages-text');
            }

            // Update plugin's default empty text
            if (undefined !== $(el).data('empty-text')) {
                cfg.emptyText = $(el).data('empty-text');
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
    $.fn.vineTable = function(options) {
        /**
         * Plugin's default configuration.
         * ---
         * @var  object
         */
        var cfg = setup.dataToCfg({
            title        : false,                        // Table title (not shown if empty)
            responsive   : true,                         // Make table responsive?
            search       : false,                        // Make table searchable?
            pages        : false,                        // Paginate table?
            pagesOptions : [10, 25, 50, 100],            // Pagination options
            pagesDefault : 10,                           // Default pagination option
            pagesSpan    : 3,                            // Max number of page numbers to show
            pagesText    : 'Items',                      // Text to show for pagination options
            emptyText    : 'No results could be found.', // Text to show when no rows are present
            onRefresh    : function(data) {}             // Called whenever dialog is refreshed
        }, this);

        // Construct plugin
        return $.vine.factory({
            name    : 'vineTable',
            cfg     : cfg,
            caller  : plugin,
            el      : this,
            arg     : setup.optionsToCfg(options),
            args    : arguments
        });
    };
})(jQuery);

