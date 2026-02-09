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