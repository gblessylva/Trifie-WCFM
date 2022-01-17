<?php
add_filter( 'wcfm_sell_items_catalog_args', function( $args ) {
	if( isset( $args['author__not_in']  ) ) unset( $args['author__not_in']  );
	$args['author'] = 1; 
	return $args;
}, 50 );
add_filter( 'wcfmmp_is_allow_single_product_multivendor_by_product', function( $is_allow, $product_id ) {
	global $WCFM, $WCFMmp, $product, $post;
	
	$product_author = wcfm_get_vendor_id_by_post( $product_id );
	if( $product_author ) {
		$is_allow = false;
	}
	return $is_allow;
}, 50, 2 );