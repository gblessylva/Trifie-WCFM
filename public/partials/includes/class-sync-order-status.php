<?php


add_action( 'woocommerce_thankyou', 'is_user_mailed', 20, 1);
function is_user_mailed( $order_id ){
    
  $order = wc_get_order($order_id);
  $order->update_meta_data('user_mailed', true);
  $order->save();
}

add_action( 'woocommerce_email_order_meta', 'add_prodigi_product_meta', 10, 3 );
function add_prodigi_product_meta( $order_obj, $sent_to_admin, $plain_text ){
	$track_number = get_post_meta($order_obj->get_order_number(), '_prodigi_tracking_number', true);
	$shipping_method = get_post_meta($order_obj->get_order_number(), '_prodigi_shipping_method', true );
	
   	$track_url = get_post_meta($order_obj->get_order_number(), '_prodigi_tracking_url', true);
	if($track_number){
       if ( $plain_text === false ) {
                echo '<h2>Tracking Information</h2>
                <p> Find the details of your order tracking information </p>
                <ul>
                <li style="list-style:none"><strong>Tracking Number:</strong> ' . $track_number . '</li>
                <li style="list-style:none"><strong>Tracking URL:</strong> ' . $track_url . '</li>
				 <li style="list-style:none"><strong>Shipping Method:</strong> ' . $shipping_method . '</li>
                </ul>';
            
         } else {
			echo "Tracking Information
                Tracking LINK: $track_url
                Tracking Number: $track_number ";	
            }
    }
}

if (strpos($_SERVER['REQUEST_URI'], "orderslist") !== false) {
    add_action('init', 'load_all_orders', 10);
}


 add_filter('wp_mail_from', function($email){
        return 'customer-support-6075@trifie.com';
    });

    add_filter('wp_mail_from_name', function($name){
        return 'Trife Support';
    });
	
register_activation_hook(__FILE__, 'sync_prodigi_orders');
 
function sync_prodigi_orders() {
    if (! wp_next_scheduled ( 'sync_order_status' )) {
    wp_schedule_event(time(), 'hourly', 'sync_order_status');
    }
}
 


add_action('sync_prodigi_orders', 'load_all_orders');
 
// function load_all_orders() {
//     // do something every hour
// }



    function load_all_orders(){
        $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
        $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
        // $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
        $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
        $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
        $shipping_price = WC()->session->get('shipping_price');
        $live_order_url = wcfm_get_option(' wcfm_prodigy_live_order_api_url');
        // var_dump($shipping_price);
        // $url = $live_order_url;
        $url = '';
        $api_key = '';
        
        $product_array = array();
        
        // Check if Application is LIVE or TEST
        if ($allow_live_mode == 'yes') {
            $url = $live_order_url;
            $api_key = $live_api_key;
        } else {
            $url = 'https://api.sandbox.prodigi.com/v4.0/orders?Top=10';
            $api_key = $test_api_key_vendor;
        }
   
    $query = new WC_Order_Query( array(
            'limit' => 20,
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
			'status' => array('wc-processing', 'wc-on-hold'),
        ) );
        $orders = $query->get_orders();
        $response = wp_remote_post( $url, array(
            'method' => 'GET',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',

            ))  );
    
    
    $response_body = wp_remote_retrieve_body( $response );
    $response_body = json_decode( $response_body, true );
// var_dump($response);
    
    // $response_order = array_reverse( $response_body['orders']);
    $response_order = $response_body['orders'];
    // Get all orders from woocommerce
    // var_dump($response_order);
    $Woo_orders = wc_get_orders( 
        array(
            'limit' => -1,
			'status' => array('wc-processing', 'wc-on-hold'),
        )
    );
		
// 		 var_dum($Woo_orders);

    // $woo_order_id_array = array();

    foreach ($Woo_orders as $Woo_order) {
        $Woo_order_id = $Woo_order->get_id();
        $Woo_order_status = $Woo_order->get_status();
//         $Woo_order_number = $Woo_order->get_order_number();
        $Woo_order_date = $Woo_order->get_date_created();
        $Woo_order_total = $Woo_order->get_total();
        $Woo_order_shipping = $Woo_order->get_shipping_total();
        $woo_order_id_array[] = $Woo_order_id;
    }

    // var_dump($woo_order_id_array);

    // Get all orders from prodigi
    $prodigi_order_id_array = array();
    $intersection = array();
  

    foreach($response_order as $order){
        $prodigi_order_id_array = intval( $order['merchantReference']);
        $unique_prodigi_order_id = intval( $order['merchantReference']);
      
        $prodigi_order_status = $order['status']['stage'];
        $shipment = $order['shipments'];
        $shipment_id = $shipment[0]['id'];
        $shipment_curier = $shipment[0]['carrier'];
        $shipment_tracking_number = $shipment[0]['tracking']['number'];
        $shipment_tracking_url = $shipment[0]['tracking']['url'];
        $product_name = $order['products'][0]['name'];
        $customer_name = $order['recipient']['name'];
		 $customer_email = $order['customer']['email'];


        $woo_order_single = wc_get_order($unique_prodigi_order_id);
        if($woo_order_single){
            $woo_order_single_status = $woo_order_single->get_status();
            $woo_order_single_number = $woo_order_single->get_order_number();
            $woo_order_single_id = $woo_order_single->get_id();
// 			if($woo_order_single_status == 'completed'){
// 				$is_mailed = get_post_meta($woo_order_single, 'user_mailed', true);
// // 				Check if user has been mailed
// 				if($is_mailed == false){
// 					WC()->mailer()->emails['WC_Email_Customer_Completed_Order']->trigger( $woo_order_single_id );
// 					$woo_order_single->update_meta_data('user_mailed', true);
					

// 				}
// 			}
            if($woo_order_single_status != 'completed'){
                if($prodigi_order_status == 'InProgress'){
                    $woo_order_single->update_status('pending');
                    // var_dump($woo_order_single_id, 'woo_order_single_id');
                    // var_dump($shipping_message);
                }elseif($prodigi_order_status == 'Complete'){
                    
                    $woo_order_single->update_meta_data('_prodigi_tracking_url', $shipment_tracking_url);
                    $woo_order_single->update_meta_data('_prodigi_tracking_number', $shipment_tracking_number);
                    $woo_order_single->update_status('completed');
                    // var_dump($prodigi_order_status);
                    $woo_order_single->update_meta_data('_prodigi_order_id', $unique_prodigi_order_id);
                    $woo_order_single->update_meta_data('_prodigi_order_status', $prodigi_order_status);
					$woo_order_single->update_meta_data('user_mailed', true);
					WC()->mailer()->emails['WC_Email_Customer_Completed_Order']->trigger( $woo_order_single );
                    
                }elseif($prodigi_order_status == 'Cancelled'){
                    $woo_order_single->update_status('cancelled');
                }
            }
        }
        // var_dump('single order', $woo_order_single_id);
    };

    
    }
    // End of load orders function 