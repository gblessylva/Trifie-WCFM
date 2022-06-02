<?php
// add_action( 'init', 'add_vendors_to_order', 10 );
add_action( 'woocommerce_email_order_meta', 'add_vendors_to_order', 10, 3 );

function add_vendors_to_order($order_obj, $sent_to_admin, $plain_text) {
    // global $WCFM, $WCFMmp, $post;
    $order_id = $order_obj->get_order_number() ;
    $order = wc_get_order( $order_id );
    $items = $order->get_items( 'line_item' );
    if(!empty($items)){
        foreach($items as $order_item_id => $item){
            $line_item = new WC_Order_Item_Product( $item );
								$product  = $line_item->get_product();
								$product_id = $line_item->get_product_id();
								$vendor_id  = wcfm_get_vendor_id_by_post( $product_id );
                                $url = wcfmmp_get_store_url( $vendor_id );
                                $vendor_name = get_user_meta( $vendor_id, 'store_name', true );
                                // $store_street = get_user_meta( $vendor_id, '_wcfm_street_1', true );
                                // $store_location = get_user_meta( $vendor_id, '_wcfm_store_location', true );
                                // $store_description = get_user_meta( $vendor_id, '_store_description', true );
                                $store_array = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
                                $store_name = $store_array['store_name'];
                                $store_phone = $store_array['phone'];
                                $store_email = $store_array['store_email'];
                                $store_address = $store_array['address'];
                                $store_street = $store_address['street_1'];
                                $store_city = $store_address['city'];
                               $store_info = "
                               <h3>Store Information</h3>
                                <ul>
                                    <li>Store :<a href='$store_url'> $store_name</a></li>
                                    <li>Phone : $store_phone</li>
                                    <li>Email : $store_email</li>
                                    <li>Address : $store_street, $store_city </li>
                                </ul>
                               ";

        if($vendor_id){
            if($plain_text){
                _e( $store_info, 'woocommerce' );
             }else{
                echo $store_info;
             }
        }

        }
    }


    // $processed_vendors = array();
	// 			if( function_exists( 'wcfm_get_vendor_store_by_post' ) ) {
	// 				$order = wc_get_order( $order_id );
	// 				if( is_a( $order , 'WC_Order' ) ) {
	// 					$items = $order->get_items( 'line_item' );
	// 					if( !empty( $items ) ) {
	// 						foreach( $items as $order_item_id => $item ) {
	// 							$line_item = new WC_Order_Item_Product( $item );
	// 							$product  = $line_item->get_product();
	// 							$product_id = $line_item->get_product_id();
	// 							$vendor_id  = wcfm_get_vendor_id_by_post( $product_id );
								
	// 							if( !$vendor_id ) continue;
	// 							if( in_array( $vendor_id, $processed_vendors ) ) continue;
								
	// 							$store_name = wcfm_get_vendor_store( $vendor_id );
    //                             var_dump($store_name);
	// 							if( $store_name ) {
	// 										$processed_vendors[$vendor_id] = $vendor_id;
	// 							}
	// 						}
	// 					}
	// 				}
	// 			}
    // var_dump($order_id);

}