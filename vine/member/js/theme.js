/**
 * Initialize theme.
 */
$(document).ready(function() {
    theme.start();
});

/**
 * Whenever window is resized.
 */
$(window).resize(function() {
    theme.refreshFooter();
    theme.refreshMenu();
});

/**
 * Base theme engine.
 * @var  object
 */
var theme = {
    /**
     * Initialize the theme.
     * @return  void
     */
    start : function() {
        this.loginMenu();
        this.mainMenu();
        this.vineUi();
        this.refreshFooter();
        this.refreshMenu();
    },

    /**
     * Activate the login menu.
     * @return  void
     */
    loginMenu : function() {
        // Local vars
        var self = this;

        // When any element outside of the top nav is clicked on
        $('html').click(function() {
            if ($('#login-menu').is(':visible')) {
                $('#login-menu').hide();
                $('#login-info').removeClass('active');
            }
        });

        // Whenever login info area is clicked on
        $('#login-info').click(function(e) {
            // Stop HTML click in event above
            e.stopPropagation();

            // Hide login menu
            if ($('#login-menu').is(':visible')) {
                $(this).removeClass('active');
                $('#login-menu').slideUp(100);
            // Show login menu
            } else {
                $(this).addClass('active');
                $('#login-menu').slideDown(100);
            }
        });
    },

    /**
     * Activate the main menu.
     * @return  void
     */
    mainMenu : function() {
        // Local vars
        var self = this;

        // Always close menu by default (phones only)
        $('#main-menu-toggle').data('opened', false);

        // Whenever menu icon is clicked on a mobile device
        $('#main-menu-toggle').click(function() {
            // Hide menu
            if ($('#main-menu').is(':visible')) {
                $('#main-menu').slideUp(200, function() {
                    $('#main-menu-toggle').data('opened', false);
                    self.refreshFooter();
                });
            // Show menu
            } else {
                $('#main-menu').slideDown(200, function() {
                    $('#main-menu-toggle').data('opened', true);
                    self.refreshFooter();
                });
            }
        });
    },

    /**
     * VineUI extensions.
     * @return  void
     */
    vineUi : function() {
        $('[data-widget="table"]').each(function() {
            var wrap   = $(this).closest('.vine-table');
            var button = $(wrap).prev().hasClass('table-button') ? $(wrap).prev() : false;

            if (button) {
                $(wrap).find('.vine-title').remove();
                $(button).appendTo($(wrap).find('.vine-search-wrap'));
            }
        });
    },

    /**
     * Refresh sticky footer.
     * @return  void
     */
    refreshFooter : function() {
        // Use relative positioning when page is bigger than window
        if ($(document).outerHeight() > $(window).outerHeight() - $('#footer').height()) {
            $('#footer').css('position', 'relative');
        // Use absolute positioning when not enough content to fill page
        } else {
            $('#footer').css('position', 'absolute');
        }
    },

    /**
     * Refresh main menu.
     * @return  void
     * @note    Run AFTER refreshFooter()!
     */
    refreshMenu : function() {
        // Reset menu height (so calculations aren't skewed)
        $('#main-menu').css('min-height', 0).show();

        // Local vars
        var self    = this;
        var isPhone = $('#vine-size-small').is(':visible');
        var cHeight = Math.max($(window).outerHeight(), $(document).outerHeight());
        var hHeight = $('#header').outerHeight(true);
        var fHeight = $('#footer').outerHeight(true);
        var opened  = $.vineQuick.toBool($('#main-menu-toggle').data('opened'));

        // Do NOT resize menu for phones
        if ( ! isPhone) {
            $('#main-menu').css('min-height', cHeight - hHeight - fHeight);
        // Hide main menu (can be toggled open via #main-menu-toggle
        } else if (false === opened) {
            $('#main-menu').hide();
            self.refreshFooter();
        // Keep main menu visible
        } else {
            $('#main-menu').show();
            self.refreshFooter();
        }
    }
};