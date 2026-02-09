// DOM ready
$(document).ready(function() {
    admins.activate();
});

/**
 * Manage Admins
 */
var admins = {
    /**
     * Activate functionality.
     * ---
     * @return  void
     */
    activate : function() {
        // Local vars
        var self = this;

        // Only activate if applicable element present
        if ($('#admins-table').length > 0) {
            self.table();
        }

        // Only activate if applicable element present
        if ($('#admins-form').length > 0) {
            self.form();
        }
    },

    /**
     * Activate manage admins area.
     * ---
     * @return  void
     */
    table : function() {
        // Whenever a delete icon is clicked
        $('#admins-table a.delete').click(function() {
            $('#admins-delete .yes').attr('href', $(this).attr('href'));
            $('#admins-delete').vineDialog('open');
            return false;
        });
    },

    /**
     * Activate add/edit form.
     * ---
     * @return  void
     */
    form : function() {
        // Toggle "Confirm Password" field
        $('#password').keyup(function() {
            if ($(this).val()) {
                $('#row-password-confirm').show();
            } else {
                $('#row-password-confirm').hide();
            }
        }).keyup();
    }
};