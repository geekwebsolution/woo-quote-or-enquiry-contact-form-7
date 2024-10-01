/** Pass product details to contact form fields */
var currentRequest = null;
jQuery("body").on("click", ".wqoecf_enquiry_button", function () {

    var $btn = jQuery(this);
    var product_id = jQuery(this).attr("data-product-id");
    var product_title = jQuery(this).attr("data-product-title");
    var product_sku = jQuery(this).attr("data-product-sku");

    $btn.addClass('wqoecf_loading').prepend('<span class="wqoecf_spinner"></span>');

    currentRequest = jQuery.ajax({
        type: 'POST',
        url: wqoecfObj.ajaxurl,
        data: {
            'action': 'wqoecf_enquiry_popup'
        },
        dataType: 'json',
        success: function (response) {
            if (response.data) {
                if (jQuery('body .wqoecf-popup-wrapper').length == 0) {
                    jQuery('body').append('<div class="wqoecf-popup-wrapper"></div>');
                }
                jQuery('.wqoecf-popup-wrapper').html(response.data.html);
                const forms = document.querySelectorAll('.wpcf7 > form');
                forms.forEach((e) => wpcf7.init(e));    // initialize cf7 after AJAX

                var popupMain = document.querySelector('.wqoecf-pop-up-box .wpcf7-form');
                popupMain.scrollTop = 0;

                wqoecf_update_inputs(product_id, product_title, product_sku);   // Update input values

                jQuery(".wqoecf-pop-up-box").fadeIn();
                $btn.removeClass('wqoecf_loading').find('.wqoecf_spinner').remove();
            }
        }
    });
});

/** Handler to prevent multiple clicks on submit button  */
jQuery(document).on("click", ".wqoecf-pop-up-box form.submitting .wpcf7-submit", function (e) {
    e.preventDefault();
    return false;
});

/**
 * Update value of input
 */
function wqoecf_update_inputs(id, title, sku) {
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-id']").val(id);
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-name']").val(title);
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-sku']").val(sku);

    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-id"]').attr("readonly", true);
    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-name"]').attr("readonly", true);
    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-sku"]').attr("readonly", true);
}

/** Function ton hide modal */
function wqoecf_hide() {
    jQuery(".wqoecf-pop-up-box").fadeOut();
    jQuery(".wqoecf-pop-up-box .wpcf7-not-valid-tip").css("display", "none");
}