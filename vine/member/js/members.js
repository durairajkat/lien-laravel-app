// DOM ready
$(document).ready(function() {
    members.activate();
});

/**
 * Manage Members
 */
var members = {
    /**
     * Activate functionality.
     * ---
     * @return  void
     */
    activate : function() {
        // Local vars
        var self = this;

        // Only activate if applicable element present
        if ($('#members-table').length > 0) {
            self.table();
        }

        // Only activate if applicable element present
        if ($('#members-form').length > 0) {
            self.form();
        }
    },

    /**
     * Activate manage members area.
     * ---
     * @return  void
     */
    table : function() {
        // Whenever a delete icon is clicked
        $('#members-table a.delete').click(function() {
            $('#members-delete .yes').attr('href', $(this).attr('href'));
            $('#members-delete').vineDialog('open');
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