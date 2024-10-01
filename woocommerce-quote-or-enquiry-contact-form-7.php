<?php
/*
Plugin Name: WooCommerce Quote or Enquiry Contact Form 7
Description: A plugin to add product enquiry button with contact form 7 
Author: Geek Code Lab
Version: 3.4.6
WC tested up to: 9.3.3
Author URI: https://geekcodelab.com/
Text Domain: woocommerce-quote-or-enquiry-contact-form-7
*/

if (!defined('ABSPATH')) exit;

define("WQOECF_BUILD", "3.4.6");

if (!defined("WQOECF_PLUGIN_DIR_PATH"))
	define("WQOECF_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("WQOECF_PLUGIN_URL"))
	define("WQOECF_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));


require_once(WQOECF_PLUGIN_DIR_PATH . 'functions.php');
require(WQOECF_PLUGIN_DIR_PATH . 'enquiry.php');

/**
 * Regisration activation
 */
register_activation_hook(__FILE__, 'wqoecf_plugin_active_quote_or_enquiry_contact_form');

function wqoecf_plugin_active_quote_or_enquiry_contact_form() {
	$options_db =  wqoecf_quote_enquiry_options();
	if (empty($options_db)) {
		do_action('wp_default_color_text_options');
	}
}

/** Trigger an admin notice if WooCommerce or Contact Form 7 is not installed.*/
if (! function_exists('wqoecf_install_require_plugins_admin_notice')) {
	function wqoecf_install_require_plugins_admin_notice() {
		if (! function_exists('WC') || ! is_plugin_active('contact-form-7/wp-contact-form-7.php')) { ?>
			<div class="error">
				<p>
					<?php
					// translators: %s is the plugin name.
					printf(__('%s is enabled but not effective. It requires both two plugins WooCommerce and Contact Form 7 in order to work.', 'woocommerce-quote-or-enquiry-contact-form-7'), 'WooCommerce Quote or Enquiry Contact Form 7');
					?>
				</p>
			</div>
	<?php
		}
	}
}
add_action('admin_notices', 'wqoecf_install_require_plugins_admin_notice');

/**
 * Set default values of color && text
 */
function wqoecf_default_color_text_options() {
	$btntext  = __("Enquiry", "woocommerce-quote-or-enquiry-contact-form-7");
	$btncolor = "#289dcc";
	$options['product_tag'] = array();
	$options['button_text'] 	= $btntext;
	$options['button_color'] 	= $btncolor;
	$options['product_single_page'] = 'on';
	$options['product_list_page'] 	= 'on';
	$options['product_categories'] 	= array();
	update_option('wqoecf_quote_or_enquiry_settings', $options);
}
add_action('wp_default_color_text_options', 'wqoecf_default_color_text_options');

/**
 * Plugin setting links
 */
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wqoecf_plugin_add_settings_link');
function wqoecf_plugin_add_settings_link($links) {
	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support', 'woocommerce-quote-or-enquiry-contact-form-7') . '</a>';
	array_unshift($links, $support_link);

	if (class_exists('WooCommerce')) {
		$settings_link = '<a href="admin.php?page=wqoecf-quote-or-enquiry-contact-form">' . __('Settings', 'woocommerce-quote-or-enquiry-contact-form-7') . '</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Register front scripts
 */
add_action('wp_enqueue_scripts', 'wqoecf_include_front_script');
function wqoecf_include_front_script() {
	wp_register_style("wqoecf-front-woo-quote", WQOECF_PLUGIN_URL . "/assets/css/wqoecf-front-style.css", array(), WQOECF_BUILD);
	wp_register_script("wqoecf-front-woo-quote", WQOECF_PLUGIN_URL . "/assets/js/woo-enquiry-front-script.js", array(), WQOECF_BUILD, true);


	wp_enqueue_style('wqoecf-front-woo-quote');
	wp_enqueue_script('wqoecf-front-woo-quote');
	wp_localize_script('wqoecf-front-woo-quote', 'wqoecfObj', array('ajaxurl' => admin_url('admin-ajax.php')));
}

/**
 * Register admin scripts
 */
add_action('admin_enqueue_scripts', 'wqoecf_admin_styles');
function wqoecf_admin_styles($hook) {
	if (is_admin() && $hook == 'woocommerce_page_wqoecf-quote-or-enquiry-contact-form') {
		$css = WQOECF_PLUGIN_URL . "/assets/css/wqoecf_admin_style.css";
		wp_enqueue_style('wqoecf-admin-style.css', $css, array(), WQOECF_BUILD);

		wp_enqueue_style("wqoecf-admin-woo-quote-select-style", WQOECF_PLUGIN_URL . "/assets/css/select2.min.css", array(), WQOECF_BUILD);
		wp_enqueue_script("wqoecf-front-woo-quote-select-script", WQOECF_PLUGIN_URL . "/assets/js/select2.min.js", array(), WQOECF_BUILD);
		wp_enqueue_script('wp-color-picker');
		// Add the color picker css file
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script("wqoecf-front-woo-quote-admin-script", WQOECF_PLUGIN_URL . "/assets/js/woo-enquiry-admin-script.js", array('jquery'), WQOECF_BUILD);
	}
}

/**
 * Add sub menu for quote or enquiry settings 
 */
add_action('admin_menu', 'wqoecf_admin_menu_quote_or_enquiry_contact_form');
function wqoecf_admin_menu_quote_or_enquiry_contact_form() {
	add_submenu_page('woocommerce', 'Quote Or Enquiry Contact Form 7', 'Quote Or Enquiry Contact Form 7', 'manage_options', 'wqoecf-quote-or-enquiry-contact-form', 'wqoecf_quote_or_enquiry_contact_form_page_setting');
}

function wqoecf_quote_or_enquiry_contact_form_page_setting() {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.', 'woocommerce-quote-or-enquiry-contact-form-7'));
	}
	require WQOECF_PLUGIN_DIR_PATH . 'options.php';
}

/**
 * Check if show enquries button is enable
 */
function wqoecf_show_enquiry_button($product_id) {

	if (!$product_id) return false;

	$allow_category = $product_categories = $product_tag = "";
	$_product = wc_get_product($product_id);

	$wqoecf_show_enquiry_button = false;
	$options = wqoecf_quote_enquiry_options();

	$disable_form = get_option_quote_wqoecf_disable_form($product_id);

	if (isset($options['allow_category']))		$allow_category = $options['allow_category'];
	if (isset($options['product_categories']))	$product_categories = $options['product_categories'];
	if (isset($options['product_tag']))			$product_tag = $options['product_tag'];

	if (empty($product_categories) && empty($product_tag)) {
		$allow_category = '';
	}

	if ($disable_form != 'yes' && $allow_category != 'on' && !empty($_product)) {
		$wqoecf_show_enquiry_button = true;
	}

	if ($disable_form != 'yes' && $allow_category == 'on' && !empty($_product) && ((!empty($product_categories) && has_term($product_categories, 'product_cat', $product_id)) || (!empty($product_tag) && has_term($product_tag, 'product_tag', $product_id)))) {
		$wqoecf_show_enquiry_button = true;
	}

	if ($wqoecf_show_enquiry_button) {
		return true;
	}

	return false;
}

/**
 * Allow enquiry to user
 */
function wqoecf_allow_enquiry_to_user() {

	$single_page = $allow_user = "";
	$options =  wqoecf_quote_enquiry_options();

	if (isset($options['product_single_page']))	$single_page = $options['product_single_page'];
	if (isset($options['allow_user']))			$allow_user = $options['allow_user'];

	if ($single_page == 'on') {
		if ($allow_user != 'on') {

			if (is_user_logged_in()) {
				return true;
			}
		} else {
			return true;
		}
	}

	return false;
}

/**
 * Add enquiry button to loop and single product
 */
function wqoecf_main() {

	$options =  wqoecf_quote_enquiry_options();

	$status = "";
	$contactform = "";
	$allow_user = "";
	$allow_category 		= '';
	$product_single_page = "";
	$product_list_page = "";
	$product_categories = array();
	$get_product_tag = array();
	$single_page = "";
	$list_page = "";
	if (isset($options['status'])) {
		$status = $options['status'];
	}
	if (isset($options['contact_form7'])) {
		$contactform = $options['contact_form7'];
	}
	if (isset($options['allow_user'])) {
		$allow_user = $options['allow_user'];
	}
	if (isset($options['product_single_page'])) {
		$product_single_page = $options['product_single_page'];
	}
	if (isset($options['product_list_page'])) {
		$product_list_page = $options['product_list_page'];
	}
	if (isset($options['allow_category'])) {
		$allow_category = $options['allow_category'];
	}
	if (isset($options['product_categories'])) {
		$product_categories = $options['product_categories'];
	}
	if (isset($options['product_tag'])) {
		$get_product_tag = $options['product_tag'];
	}

	if ($status == 'on' && !empty($contactform)) {
		$list_page = "";
		$options =  wqoecf_quote_enquiry_options();
		if (isset($options['product_list_page'])) $list_page = $options['product_list_page'];

		if ($list_page == 'on') {
			if ($allow_user != 'on') {
				if (is_user_logged_in()) {
					add_filter('woocommerce_loop_add_to_cart_link', 'wqoecf_shop_page_enquiry_button', 10, 2);
					add_action('wp_footer', 'wqoecf_quote_enquiry_script');
				}
			} else {
				add_filter('woocommerce_loop_add_to_cart_link', 'wqoecf_shop_page_enquiry_button', 10, 2);
				add_action('wp_footer', 'wqoecf_quote_enquiry_script');
			}
		}

		if (wqoecf_allow_enquiry_to_user()) {
			add_action('woocommerce_single_product_summary', 'wqoecf_single_page_enquiry_button', 30);
			add_action('wp', 'wqoecf_single_page_remove_add_cart');
			add_action('wp_footer', 'wqoecf_quote_enquiry_script');
		}
	}
}
add_action("init", "wqoecf_main");

/**
 * Shop page enquiry button
 */
function wqoecf_shop_page_enquiry_button($button, $product) {
	global $post;

	if (wqoecf_show_enquiry_button($product->get_id())) {
		if (! wp_script_is('enqueued', 'wqoecf-front-woo-quote')) {
			wp_enqueue_style('wqoecf-front-woo-quote');
			wp_enqueue_script('wqoecf-front-woo-quote');
		}

		$btntext = "";
		$options = wqoecf_quote_enquiry_options();
		if (isset($options['button_text']))		$btntext = $options['button_text'];

		$pro_title = get_the_title($product->get_id());
		$product_sku = $product->get_sku();
		$options = wqoecf_quote_enquiry_options();
		if (isset($options['wqoecf_hide_cart_btn']) && !empty($options['wqoecf_hide_cart_btn']) && $options['wqoecf_hide_cart_btn'] == 'on') {
			$button = sprintf('<a class="wqoecf_enquiry_button" href="javascript:void(0)"  data-product-id="%s" data-product-title="%s" data-product-sku="%s"><span class="wqoecf_eq_icon"></span>%s</a>', esc_attr($product->get_id()), esc_attr($pro_title), esc_attr($product_sku), esc_attr($btntext));
		} else {
			$button .= sprintf('<div class="wqoecf_enquiry_button_wrapper"><a class="wqoecf_enquiry_button" href="javascript:void(0)"  data-product-id="%s" data-product-title="%s" data-product-sku="%s"><span class="wqoecf_eq_icon"></span>%s</a></div>', esc_attr($product->get_id()), esc_attr($pro_title), esc_attr($product_sku), esc_attr($btntext));
		}
	}

	return $button;
}

/**
 * Product single page enquiry button
 */
function wqoecf_single_page_enquiry_button() {

	// Check if the current page is product page
	if (!is_product()) {
		return;
	}
	// Get the global product object
	global $product;
	if (!$product) {
		return;
	}

	$product_id = $product->get_id();

	if (wqoecf_show_enquiry_button($product_id)) {
		if (! wp_script_is('enqueued', 'wqoecf-front-woo-quote')) {
			wp_enqueue_style('wqoecf-front-woo-quote');
			wp_enqueue_script('wqoecf-front-woo-quote');
		}

		$btntext = "";
		$options	= wqoecf_quote_enquiry_options();
		if (isset($options['button_text']))		$btntext = $options['button_text'];

		$pro_title = get_the_title($product_id);
		$product_sku = wc_get_product()->get_sku();

		printf('<a class="wqoecf_enquiry_button" href="javascript:void(0)"  data-product-id="%s" data-product-title="%s" data-product-sku="%s" ><span class="wqoecf_eq_icon"></span>%s</a>', esc_attr($product_id), esc_attr($pro_title), esc_attr($product_sku), esc_attr($btntext));
	}
}

/**
 * Single product remove add to cart button
 */
function wqoecf_single_page_remove_add_cart() {
	$product_id = get_the_ID(); // the ID of the product to check
	$_product = wc_get_product($product_id);
	if (wqoecf_show_enquiry_button($product_id)) {
		$options = wqoecf_quote_enquiry_options();
		if (isset($options['wqoecf_hide_cart_btn']) && !empty($options['wqoecf_hide_cart_btn']) && $options['wqoecf_hide_cart_btn'] == 'on') {
			$product_get_type = $_product->get_type();
			remove_action('woocommerce_' . $product_get_type . '_add_to_cart', 'woocommerce_' . $product_get_type . '_add_to_cart', 30);
		}
	}
}

/**
 * Enquiry popup html
 */
function wqoecf_quote_enquiry_script() {
	$product_id = get_the_ID();
	$options =  wqoecf_quote_enquiry_options();

	if (!isset($options['product_list_page']) || !is_shop() && !is_product()) return __return_false();

	if (isset($options['product_list_page'])) {
		if ($options['product_list_page'] == '' && !is_product()) return __return_false();
		if ($options['product_list_page'] != 'on' && !is_product())
			if (!is_user_logged_in()) return __return_false();
	}

	$contactform = $form_title = '';

	$options	= wqoecf_quote_enquiry_options();
	$form_title		= "Product Enquiry";

	if (isset($options['contact_form7']))			$contactform 	= $options['contact_form7'];
	if (isset($options['wqoecf_form_title'])) 		$form_title 	= $options['wqoecf_form_title'];
	ob_start();
	?>
	<div class="wqoecf-pop-up-box" style="display: none;">
		<button class="wqoecf_close" onclick="wqoecf_hide()"><span></span><span></span></button>
		<div>
			<p class="wqoecf_form_title"><?php esc_html_e($form_title); ?></p>
			<?php echo do_shortcode('[contact-form-7 id="' . esc_attr($contactform) . '"]'); ?>
		</div>
	</div>
<?php
	echo ob_get_clean();
}

/**
 * Enquiry button header additional style
 */
add_action('wp_head', 'wqoecf_set_button_color');
function wqoecf_set_button_color() { ?>
	<style>
		<?php
		$options =  wqoecf_quote_enquiry_options();
		$btncolor = "";
		if (isset($options['button_color'])) {
			$btncolor = $options['button_color'];
		?>.woocommerce a.wqoecf_enquiry_button {
			background-color: <?php esc_attr_e($btncolor); ?>;
		}

		<?php
		}  ?>
	</style>
<?php
}

/** Shortcode for Enquiry button */
add_shortcode('wqoecf_button_quote', 'wqoecf_req_button_quote');
function wqoecf_req_button_quote($atts, $content = null) {
	if (! wp_script_is('enqueued', 'wqoecf-front-woo-quote')) {
		wp_enqueue_style('wqoecf-front-woo-quote');
		wp_enqueue_script('wqoecf-front-woo-quote');
	}
	ob_start();
	$product_id = isset($atts['product']) ? esc_attr($atts['product']) : false;
	wqoecf_render_button($product_id);
	return ob_get_clean();
}

function wqoecf_render_button($product_id = false) {
	$btntext = '';
	$options = wqoecf_quote_enquiry_options();
	if (! $product_id) {
		global $product, $post;
		if (!$product instanceof WC_Product && $post instanceof WP_Post) {
			$product = wc_get_product($post->ID);
		}
	} else {
		$product = wc_get_product($product_id);
	}

	global $woocommerce_loop;

	if ($product) {
		$product_id = $product->get_id();
		$product_sku = $product->get_sku();

		$template_button = 'add-to-quote.php';
		$wcloop_name = ! is_null($woocommerce_loop) && ! is_null($woocommerce_loop['name']) ? $woocommerce_loop['name'] : '';

		$pro_title = get_the_title($product_id);

		if (isset($options['button_text'])) {
			$btntext = $options['button_text'];
		}

		$button = sprintf('<a class="wqoecf_enquiry_button" href="javascript:void(0)" data-product-id="%s" data-product-title="%s" data-product-sku="%s" ><span class="wqoecf_eq_icon"></span>%s</a>', esc_attr($product_id), esc_attr($pro_title), esc_attr($product_sku), esc_attr($btntext));

		_e($button);
	}
}

/**
 * Added HPOS support for woocommerce
 */
add_action('before_woocommerce_init', 'wqoecf_before_woocommerce_init');
function wqoecf_before_woocommerce_init() {
	if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
	}
}
