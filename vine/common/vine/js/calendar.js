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