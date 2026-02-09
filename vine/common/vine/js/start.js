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