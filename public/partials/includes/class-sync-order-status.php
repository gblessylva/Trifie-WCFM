<?php


// function synchronize_order_status() {
if (strpos($_SERVER['REQUEST_URI'], "orderslist") !== false) {
    add_action('init', 'load_all_orders', 10);

    function load_all_orders(){
        
    add_filter('wp_mail_from', function($email){
        return 'customer-support-6075@trifie.com';
    });

    add_filter('wp_mail_from_name', function($name){
        return 'Trife Support';
    });
        

        $query = new WC_Order_Query( array(
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
        ) );
        $orders = $query->get_orders();
        $url = "https://api.sandbox.prodigi.com/v4.0/orders?Top=40&Skip=0";
        $api_key = 'test_f47cb388-11bb-4453-b337-62574d0eae54';
        $response = wp_remote_post( $url, array(
            'method' => 'GET',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',

            ))  );
    
    
    $response_body = wp_remote_retrieve_body( $response );
    $response_body = json_decode( $response_body, true );
    
    // $response_order = array_reverse( $response_body['orders']);
    $response_order = $response_body['orders'];
    // Get all orders from woocommerce
    // var_dump($response_order);
    $Woo_orders = wc_get_orders( 
        array(
            'limit' => -1,
        )
    );

    // $woo_order_id_array = array();

    foreach ($Woo_orders as $Woo_order) {
        $Woo_order_id = $Woo_order->get_id();
        $Woo_order_status = $Woo_order->get_status();
        $Woo_order_number = $Woo_order->get_order_number();
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
        // var_dump($order['merchantReference']);
        // $unique_order_id = array_unique( $prodigi_order_id_array );
        // var_dump($unique_order_id);

        $prodigi_order_status = $order['status']['stage'];
        $shipment = $order['shipments'];
        $shipment_id = $shipment[0]['id'];
        $shipment_curier = $shipment[0]['carrier'];
        $shipment_tracking_number = $shipment[0]['tracking']['number'];
        $shipment_tracking_url = $shipment[0]['tracking']['url'];
        $product_name = $order['products'][0]['name'];
        $customer_name = $order['recipient']['name'];
		 $customer_email = $order['customer']['email'];
        

        $shipping_message = 'Your order '.$product_name.' with order ID  <strong>'. $unique_prodigi_order_id.'</strong> has been completed. The tracking number is: ' . $shipment_tracking_number . '.' . ' You can track your order at: ' . $shipment_tracking_url . '';
//         var_dump( $order['recipient']);


        $woo_order_single = wc_get_order($unique_prodigi_order_id);
        if($woo_order_single){
            $woo_order_single_status = $woo_order_single->get_status();
            $woo_order_single_number = $woo_order_single->get_order_number();
            $woo_order_single_id = $woo_order_single->get_id();
// 			 $customer_email = $woo_order_single->get_billing_email();
            // var_dump($woo_order_single_id, 'woo_order_single_id');
//             var_dump($customer_email);
            if($woo_order_single_status != 'completed'){
                // var_dump($shipment_tracking_number);
                if($prodigi_order_status == 'InProgress'){
                    $woo_order_single->update_status('pending');
                    // var_dump($woo_order_single_id, 'woo_order_single_id');
                    // var_dump($shipping_message);
                }elseif($prodigi_order_status == 'Complete'){
                    $woo_order_single->update_status('completed');
                    // var_dump($prodigi_order_status);
                    $woo_order_single->update_meta_data('_prodigi_order_id', $unique_prodigi_order_id);
                    $woo_order_single->update_meta_data('_prodigi_order_status', $prodigi_order_status);
                    $to = $woo_order_single->get_billing_email();
// 					var_dump($to);

                   
//                     function send_custom_order_email(){
//                         $to = 'gblessylva@gmail.com';
//                         $to = $woo_order_single->get_billing_email();
                        $subject = 'Your order has been completed';
                        $headers = array('Content-Type: text/html; charset=UTF-8');
						$headers[] = 'Cc: gblessylva@gmail.com';
                        // $message = '<h1> Your order has <strong>been<strong> completed </h1>';
                        $html =  '<div style=" 
                        background-color: #085fe0;
                        padding: 20px 10px;
                        text-align: center;
                        color: white;">
                        <h2 style="color:white; ">Your Order has been completed with the following Order details</h2>
                    </div>
                    <div style="
                    padding: 15px;
                    width: 100%;
                    ">
                        <p>Hi' . $customer_name .' </p>
                        <p>Just to let you know your order has been completed with the following infomation</p>
                        
                        <table style="width:100%">
                            <tbody style="width: 100%">
                                <tr style="
                                padding: 10px;
                                width: 100%;
                                text-align: left;
                                display: flex;
                                flex-direction: row;
                                justify-content: space-between;
                                " >
                                    <th class="order-number">Order ID</th>
                                    <td># '.$unique_prodigi_order_id. '</td>
                                </tr>
                                <tr style="
                                padding: 10px;
                                width: 100%;
                                text-align: left;
                                display: flex;
                                flex-direction: row;
                                justify-content: space-between;>
                                    <th>Product Name</th>
                                    <td>'  . $product_name .  '</td>
                                </tr>
                                <tr style="
                                padding: 10px;
                                width: 100%;
                                text-align: left;
                                display: flex;
                                flex-direction: row;
                                justify-content: space-between;>
                                    <th>Trackin Number</th>
                                    <td>' . $shipment_tracking_number . '</td>
                                </tr>
                                <tr>
                                    <th>Tracking URL</th>
                                    <td><a href=" ' . $shipment_tracking_url.' "> '. $shipment_tracking_url .' </a></td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <p>Thank you for chosing siteName</p>
                    </div>';

                        ob_start();
                        include( plugin_dir_path( __FILE__ ) . 'class-order-complete-template.php');
                        $message = ob_get_clean();

                        wp_mail( $to, $subject, $message, $headers );
                                                
//                     }
//                     send_custom_order_email();
                    
                }elseif($prodigi_order_status == 'Cancelled'){
                    $woo_order_single->update_status('cancelled');
                }
            }
        }
        // var_dump('single order', $woo_order_single_id);
    };

    // var_dump($prodigi_order_id_array);
    // $intersection = array_intersect($woo_order_id_array, $prodigi_order_id_array);
    // var_dump($intersection);


    // foreach($intersection as $inter){
    //     $woo_order_single = wc_get_order($inter);
    //     if($woo_order_single){
    //         $woo_order_single_status = $woo_order_single->get_status();
    //         $woo_order_single_number = $woo_order_single->get_order_number();
    //         $woo_order_single_id = $woo_order_single->get_id();
    //         var_dump($woo_order_single_id, $woo_order_single_status);
    //     }
    //     // var_dump('single order', $woo_order_single->get_id());
    
    // }
    }
    // End of load orders function
}
// end of if statement

