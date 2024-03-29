<?php
function enqueue_select2_jquery() {
    wp_register_style( 'select2css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_select2_jquery' );



add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_billing_state', 'change_default_checkout_state' );
add_filter( 'default_checkout_billing_postcode', 'change_default_checkout_postcode' );
add_filter( 'default_checkout_billing_city', 'change_default_checkout_city' );

function change_default_checkout_country() {
    // Get the shipping country
    $shipping_country = WC()->customer->get_shipping_country();

  return $shipping_country; // country code
}

function change_default_checkout_state() {
    // Get the shipping state
    $shipping_state = WC()->customer->get_shipping_state();
  return $shipping_state; // state code
}
function change_default_checkout_postcode(){
    // Get the shipping postcode
  $shipping_postcode = WC()->customer->get_shipping_postcode();
  return $shipping_postcode; // postcode
}
function change_default_checkout_city(){
    // Get the shipping city
  $shipping_city = WC()->customer->get_shipping_city();
  return $shipping_city; // city
} 

class WooCommerce_Hide_Password_Protected_Products {

	/**
	 * The Constructor
	 */
	public function __construct() {
		add_action( 'pre_get_posts', array( $this, 'alter_product_query' ), 11 );
	}

	/**
	 * Alter the WooCommerce product query
	 *
	 * @param $q
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function alter_product_query( $q ) {

		if ( ! is_admin() && ! is_single() && isset( $q->query ) && isset( $q->query['post_type'] ) && 'product' == $q->query['post_type'] ) {
			add_filter( 'posts_where', array( $this, 'exclude_protected_products' ) );
		}
	}

	/**
	 * Prevent password protected products appearing in the loops
	 *
	 * @param  string $where
	 *
	 * @return string
	 */
	public function exclude_protected_products( $where ) {
		global $wpdb;
		$where .= " AND {$wpdb->posts}.post_password = ''";

		return $where;
	}
}

// Bootstrap function
function __woocommerce_hide_password_protected_products_main() {
	new  WooCommerce_Hide_Password_Protected_Products();
}

// Load on plugin_loaded
add_action( 'plugins_loaded', '__woocommerce_hide_password_protected_products_main', 11 );