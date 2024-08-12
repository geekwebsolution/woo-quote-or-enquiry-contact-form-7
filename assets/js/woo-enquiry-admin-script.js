jQuery(document).ready(function() {

    jQuery('body').on("submit","#wqoecf_enquiry_pro",function(event) {
        // Prevent the form from submitting
        event.preventDefault();
        
        // Get the value of the dropdown and checkbox
        var selectedForm = jQuery('#wqoecf-forms').val(); // Corrected ID from 'wqoecf-forms' to 'wqoecf_forms'
        var isStatusChecked = jQuery('#wqoecf-status').is(':checked');
        
        // Check if the dropdown is empty and the checkbox is checked
        if (selectedForm === "" && isStatusChecked) {
            // Alert validation message
            if(jQuery('.wqoecf-box .wqoecf-error-msg').length == 0) {
                jQuery('#wqoecf-forms').closest('td').find('br').after('<span class="wqoecf_invalid_field_text">Please select at least one contact form 7.</span>');
                jQuery('.wqoecf-box').prepend('<div class="notice notice-error wqoecf-error-msg is-dismissible"><p>Please select contact form 7.</p></div>');
            }
            window.scrollTo(0, 0);
        } else {
            // Submit the form if validation passes
            this.submit();
        }
    });
});