<?php
if (!defined('ABSPATH')) exit;

if (isset($_POST['wqoecf_wpnonce'])) {
	$wqoecf_hide_cart_btn = $wqoecf_allow_user 	= $wqoecf_product_single_page =	$wqoecf_product_list_page = $wqoecf_allow_category = $wqoecf_pro_categories = $wqoecf_product_tag = $wqoecf_status 	= $wqoecf_select_form = $wqoecf_color = $wqoecf_text = $form_title = "";

	if (isset($_POST['wqoecf_status'])) 				$wqoecf_status 				= sanitize_text_field($_POST['wqoecf_status']);
	if (isset($_POST['wqoecf-allow-user']))  			$wqoecf_allow_user 			= sanitize_text_field($_POST['wqoecf-allow-user']);	
	if (isset($_POST['wqoecf-hide-cart-btn']))  		$wqoecf_hide_cart_btn 		= sanitize_text_field($_POST['wqoecf-hide-cart-btn']);	
	if (isset($_POST['wqoecf-product-single-page'])) 	$wqoecf_product_single_page = sanitize_text_field($_POST['wqoecf-product-single-page']);
	if (isset($_POST['wqoecf-product-list-page']))  	$wqoecf_product_list_page 	= sanitize_text_field($_POST['wqoecf-product-list-page']);
	if (isset($_POST['wqoecf-allow-category']))  		$wqoecf_allow_category 		= sanitize_text_field($_POST['wqoecf-allow-category']);
	if (isset($_POST['wqoecf_forms']))  				$wqoecf_select_form 		= sanitize_text_field($_POST['wqoecf_forms']);
	if (isset($_POST['wqoecf-pro-categories']))  		$wqoecf_pro_categories 		= $_POST['wqoecf-pro-categories'];
	if (isset($_POST['wqoecf-products-tags']))  		$wqoecf_product_tag 		= $_POST['wqoecf-products-tags'];
	if (isset($_POST['wqoecf_btncolor']))  				$wqoecf_color 				= sanitize_text_field($_POST['wqoecf_btncolor']);
	if (isset($_POST['wqoecf_btntext']))  				$wqoecf_text 				= sanitize_text_field($_POST['wqoecf_btntext']);
	if (isset($_POST['wqoecf_title']))  				$form_title 				= sanitize_text_field($_POST['wqoecf_title']);
	
	$options['status'] 				= $wqoecf_status;
	$options['allow_user'] 			= $wqoecf_allow_user;
	$options['wqoecf_hide_cart_btn']= $wqoecf_hide_cart_btn;
	$options['product_single_page']	= $wqoecf_product_single_page;
	$options['product_list_page'] 	= $wqoecf_product_list_page;
	$options['allow_category'] 		= $wqoecf_allow_category;
	$options['product_categories'] 	= $wqoecf_pro_categories;
	$options['product_tag'] 		= $wqoecf_product_tag;
	$options['contact_form7']		= $wqoecf_select_form;
	$options['button_color'] 		= $wqoecf_color;
	$options['button_text'] 		= $wqoecf_text;
	$options['wqoecf_form_title'] 	= $form_title;

	$nonce = $_POST['wqoecf_wpnonce'];

	if ($wqoecf_status == "on" && empty($wqoecf_select_form)) {
		$validation = failure_option_msg_wqoecf('Please select contact form 7.');
	} else {
		if (wp_verify_nonce($nonce, 'wqoecf_nonce')) {
			update_option('wqoecf_quote_or_enquiry_settings', $options);
			$successmsg = success_option_msg_wqoecf('Settings Saved!');
		} else {
			$errormsg = failure_option_msg_wqoecf('An error has occurred.');
		}
	}
}

$options_db 	=  wqoecf_quote_enquiry_options();
$status 		= "";
$contactform	= "";
$color 			= "";
$text 			= __("Enquiry","woocommerce-quote-or-enquiry-contact-form-7");
$form_title		= __("Product Enquiry","woocommerce-quote-or-enquiry-contact-form-7");
$allow_user 	= '';
$wqoecf_hide_cart_btn 	= 'on';
$single_page 	= '';
$list_page 		= '';
$allow_category = '';
$pro_categories	= array();
$get_product_tags	= array();

if (isset($options_db['status'])) {
	$status = $options_db['status'];
}
if (isset($options_db['allow_user'])) {
	$allow_user = $options_db['allow_user'];
}
if (isset($options_db['wqoecf_hide_cart_btn'])) {
	$wqoecf_hide_cart_btn = $options_db['wqoecf_hide_cart_btn'];
}
if (isset($options_db['product_single_page'])) {
	$single_page = $options_db['product_single_page'];
}
if (isset($options_db['product_list_page'])) {
	$list_page = $options_db['product_list_page'];
}
if (isset($options_db['allow_category'])) {
	$allow_category = $options_db['allow_category'];
}
if (isset($options_db['product_categories'])) {
	$pro_categories = $options_db['product_categories'];
}
if (isset($options_db['product_tag'])) {
	$get_product_tags = $options_db['product_tag'];
}
if (isset($options_db['contact_form7'])) {
	$contactform = $options_db['contact_form7'];
}
if (isset($options_db['button_color'])) {
	$color = $options_db['button_color'];
}
if (isset($options_db['button_text'])) {
	$text = $options_db['button_text'];
}
if (isset($options_db['wqoecf_form_title'])) {
	$form_title = $options_db['wqoecf_form_title'];
}
?>

<div class="wqoecf-box">
	<?php
	if (isset($successmsg)) {
		_e($successmsg);
	}
	
	if (isset($errormsg)) {
		_e($errormsg);
	}
	if (isset($validation)) {
		_e($validation);
	} ?>

	<div class="wqoecf-main-box">
		<div class="wqoecf-title-sec">
			<h1 class="wqoecf-title"><?php esc_html_e('WooCommerce Quote or Enquiry Contact Form 7','woocommerce-quote-or-enquiry-contact-form-7'); ?></h1>
		</div>

		<div class='wqoecf_inner'>

			<form method="post" name="wqoecf_enquiry_pro" id="wqoecf_enquiry_pro" enctype="multipart/form-data">
				<table class="form-table">

					<tbody>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Status','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-status" name="wqoecf_status" value="on" <?php if ($status == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php esc_html_e('Allow Guest User','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-allow-user" name="wqoecf-allow-user" value="on" <?php if ($allow_user == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>

								<span class="tooltip-msg"><?php esc_html_e('if Enable it will be guest user to get Enquiry without registration.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Hide add to cart button','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-hide-cart-btn" name="wqoecf-hide-cart-btn" value="on" <?php if ($wqoecf_hide_cart_btn == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>

								<span class="tooltip-msg"><?php esc_html_e('if Enable it will be remove the add to cart button from single product page.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
							</td>
						</tr>
						<tr valign="top">

							<th scope="row"><?php esc_html_e('Product Single Page','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>

							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-product-single-page" name="wqoecf-product-single-page" value="on" <?php if ($single_page == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>
								<span class="tooltip-msg"><?php esc_html_e('if Enable Enquiry Button will be Display on Product Single Page.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
							</td>
						</tr>
						<tr valign="top">

							<th scope="row"><?php esc_html_e('Product List Page','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>

							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-product-list-page" name="wqoecf-product-list-page" value="on" <?php if ($list_page == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>
								<span class="tooltip-msg"><?php esc_html_e('if Enable Enquiry Button will be Display on Product List Page.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
							</td>
						</tr>

						<tr valign="top">

							<th scope="row"><?php esc_html_e('Allow Specific Product','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>

							<td>
								<label class="wqoecf-switch wqoecf-switch-quote_status">
									<input type="checkbox" id="wqoecf-allow-category" name="wqoecf-allow-category" value="on" <?php if ($allow_category == 'on') { esc_attr_e("checked"); } ?>>
									<span class="wqoecf-slider wqoecf-round"></span>
								</label>
								<span class="tooltip-msg"><?php esc_html_e('if Enable Enquiry Button will be Display on selected categories and tags.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
							</td>
						</tr>

						<?php $dis_cls = ($allow_category == 'on') ? 'wqoecf-show-dropdown' : ''; ?>

						<tr valign="top" class="wqoecf-product-cate-cls <?php esc_attr_e($dis_cls); ?>">
							<th scope="row"><?php esc_html_e('Select Product Categories','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>								
								<?php
								$categories = get_terms(
									array(
										'taxonomy'   => 'product_cat',
										'orderby'    => 'name',
										'hide_empty' => true,
									)
								); ?>
								<select multiple name="wqoecf-pro-categories[]" id="wqoecf-pro-categories" class="wqoecf-select-multiple">
									<?php
									$select = 'selected';
									foreach ($categories as $key => $value) {
										printf( '<option value="%s" %s>%s</option>', esc_attr($value->slug), (!empty($pro_categories) && in_array($value->slug, $pro_categories) ? esc_attr($select) : ''), esc_attr($value->name) );
									} ?>
								</select>
							</td>
						</tr>

						<tr valign="top" class="wqoecf-product-cate-cls <?php esc_attr_e($dis_cls); ?>">
							<th scope="row"><?php esc_html_e('Select Product Tags','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>								
								<?php
								$pro_tags = get_terms(
									array(
										'taxonomy'   => 'product_tag',
										'orderby'    => 'name',
										'hide_empty' => true,
									)
								); ?>
								<select multiple name="wqoecf-products-tags[]" id="wqoecf-products-tags" class="wqoecf-select-multiple">									
									<?php  
									$select = 'selected';
									foreach ($pro_tags as $tkey => $tval) {
										printf( '<option value="%s" %s>%s</option>', esc_attr($tval->slug), (!empty($get_product_tags) && in_array($tval->slug, $get_product_tags) ? $select : '' ), esc_attr($tval->name));
									}
									?>
								</select>
							</td>
						</tr>

						<tr class="wqoecf-contact-form-tr" valign="top">
							<th scope="row"><?php esc_html_e('Select Contact Form 7','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<select name="wqoecf_forms" id="wqoecf-forms">
									<option value="">--Select--</option>
									<?php
									$wqoecf_get_form = get_posts(array(
										'post_type'     => 'wpcf7_contact_form',
										'posts_per_page' => -1,
										'post_status'    => 'publish'
									));
									foreach ($wqoecf_get_form as $wqoecf_form) {
										printf( '<option value="%s" %s> %s (#%s)</option>', $wqoecf_form->ID, selected($wqoecf_form->ID, $contactform, false), esc_attr($wqoecf_form->post_title), esc_attr($wqoecf_form->ID));
									} ?>
								</select>
								<br>
								<span class="tooltip-msg"><?php esc_html_e('Select Contact form 7 which is use for product enquiry.','woocommerce-quote-or-enquiry-contact-form-7'); ?></span>
								<div class="wqoecf-contact-note">

									<b><?php esc_html_e('Note :-','woocommerce-quote-or-enquiry-contact-form-7'); ?> </b>
									<p><?php esc_html_e('You can pass product details in contact form. Use this field names in contact form: product-name, product-id and product-sku.','woocommerce-quote-or-enquiry-contact-form-7'); ?> </p>
									<div class="wqoecf-example">
										<p class="wqoecf-note"><b><?php esc_html_e('Example :-','woocommerce-quote-or-enquiry-contact-form-7'); ?></b> </p>
										<xmp><label> <?php esc_html_e('Product Name','woocommerce-quote-or-enquiry-contact-form-7'); ?> :- [text product-name] </label></xmp>
										<xmp><label> <?php esc_html_e('Product ID','woocommerce-quote-or-enquiry-contact-form-7'); ?> :- [text product-id] </label></xmp>
										<xmp><label> <?php esc_html_e('Product SKU','woocommerce-quote-or-enquiry-contact-form-7'); ?> :- [text product-sku] </label></xmp>
									</div>
								</div>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php esc_html_e('Popup Title','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<input type="text" name="wqoecf_title" class="wqoecf_btntext" value="<?php esc_html_e($form_title); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Button Text','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<input type="text" name="wqoecf_btntext" class="wqoecf_btntext" value="<?php esc_html_e($text); ?>">
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Button Color','woocommerce-quote-or-enquiry-contact-form-7'); ?></th>
							<td>
								<input type="text" name="wqoecf_btncolor" class="wqoecf_colorpicker" value="<?php esc_html_e($color); ?>">
							</td>
						</tr>
					</tbody>
				</table>

				<div class="wqoecf-btn-box">
					<?php $nonce = wp_create_nonce('wqoecf_nonce'); ?>
					<input type="hidden" name="wqoecf_wpnonce" value="<?php esc_attr_e($nonce); ?>">

					<input class="button button-primary button-large wqoecf_submit" type="submit" name="wqoecf_add_form" id="wqoecf_submit" value="Save Setting" />
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	(function($) {
		$(function() {
			// Add Color Picker to all inputs that have 'color-field' class
			$('.wqoecf_colorpicker').wpColorPicker();

			// Add select2 for choose multiple value in drop dwon field
			$('#wqoecf-pro-categories').select2({
				placeholder: "<?php esc_attr_e("Select product category","woocommerce-quote-or-enquiry-contact-form-7"); ?>",
				minimumInputLength: 2
			});
			$('#wqoecf-products-tags').select2({
				placeholder: "<?php esc_attr_e("Select product tag","woocommerce-quote-or-enquiry-contact-form-7"); ?>",
				minimumInputLength: 2,
			});
			$("#wqoecf-allow-category").on("click",function() {
				$(".wqoecf-product-cate-cls").toggleClass("wqoecf-show-dropdown");
			});
		});
	})(jQuery);
</script>