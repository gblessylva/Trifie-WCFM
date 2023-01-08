<?php
function add_prodigi_order_woocommerce_email( $email_classes ) {

	// include our custom email class
	require_once( 'includes/class-trife-custom-order-mail.php' );

	// add the email class to the list of email classes that WooCommerce loads
	$email_classes['WC_Prodigi_Copleted_Order_Email'] = new WC_Prodigi_Order_Email();

	return $email_classes;

}
add_filter( 'woocommerce_email_classes', 'add_prodigi_order_woocommerce_email' );

?>