/** Pass product details to contact form fields */
var currentRequest = null;
jQuery("body").on("click", ".wqoecf_enquiry_button", function () {
    var product_id = jQuery(this).attr("data-product-id");
    var product_title = jQuery(this).attr("data-product-title");
    var product_sku = jQuery(this).attr("data-product-sku");
    wqoecf_update_inputs(product_id, product_title, product_sku);
    jQuery(".wqoecf-pop-up-box").fadeIn();
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

/** Function to hide modal */
function wqoecf_hide() {
    jQuery.when(
        jQuery(".wqoecf-pop-up-box").fadeOut()
    ).done(function () {
        try {
            grecaptcha.reset();
        } catch (e) { }
        try {
            wpcf7.reset(document.querySelector('.wpcf7-form'));
        } catch (ev) {
            wpcf7.init(document.querySelector('.wpcf7-form'));
        }
    });
    jQuery(".wqoecf-pop-up-box .wpcf7-not-valid-tip").css("display", "none");
}