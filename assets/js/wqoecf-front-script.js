/** Pass product details to contact form fields */
jQuery("body").on("click", ".wqoecf_enquiry_button", function () {
    var loading_img_path = jQuery(".wqoecf-pop-up-box").attr("data-loader-path");
    jQuery("body").append('<div class="wqoecf_loading"><img src="' + loading_img_path + '" class="wqoecf_loader"></div>');
    var loading = jQuery(".wqoecf_loading");
    loading.show();
    var product_id    = jQuery(this).attr("data-product-id");
    var product_title = jQuery(this).attr("data-product-title");
    var product_sku   = jQuery(this).attr("data-product-sku");

    jQuery(".wqoecf-pop-up-box .wpcf7 > form")[0].reset();
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-id']").val(product_id);
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-name']").val(product_title);
    jQuery("div.wqoecf-pop-up-box .wpcf7 input[name='product-sku']").val(product_sku);

    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-id"]').attr("readonly", true);
    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-name"]').attr("readonly", true);
    jQuery('.wqoecf-pop-up-box div.wpcf7>form input[name="product-sku"]').attr("readonly", true);
    loading.remove();

    jQuery(".wqoecf-pop-up-box").fadeIn();
});

/** Handler to prevent multiple clicks on submit button  */
jQuery(document).on("click", ".wqoecf-pop-up-box form.submitting .wpcf7-submit", function (e) {
    e.preventDefault();
    return false;
});

/** Function ton hide modal */
function wqoecf_hide() {
    jQuery(".wqoecf-pop-up-box").fadeOut();
    jQuery(".wqoecf-pop-up-box .wpcf7-not-valid-tip").css("display", "none");
}
