/**
 * Initialize login screen.
 */
$(document).ready(function() {
    login.start();
});

/**
 * Whenever window is resized.
 */
$(window).resize(function() {
    login.refresh();
});

/**
 * Login engine.
 * @var  object
 */
var login = {
    /**
     * Initialize the login screen.
     * @return  void
     */
    start : function() {
        this.refresh();
    },

    /**
     * Position the login screen.
     * @return  void
     */
    refresh : function() {
        // Never position on phones
        if ($('#vine-size-small').is(':visible')) {
            $('#login-wrap').css({
                'position'      : 'static',
                'margin-top'    : 'auto',
                'margin-right'  : 'auto',
                'margin-bottom' : 'auto',                
                'margin-left'   : 'auto',
                'top'           : 'auto',
                'left'          : 'auto'
            });
        // Center everything on tablets and desktops
        } else {
            $('#login-wrap').css({
                'position'    : 'absolute',
                'left'        : '50%',
                'top'         : '50%',
                'margin-left' : -$('#login-wrap').outerWidth() / 2,
                'margin-top'  : -$('#login-wrap').outerHeight() / 2
            });
        }
    }
};