<?php


// function synchronize_order_status() {
if (strpos($_SERVER['REQUEST_URI'], "orderslist") !== false) {
    add_action('init', 'load_all_orders', 10);

    function load_all_orders(){
        $query = new WC_Order_Query( array(
            'limit' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
            'return' => 'ids',
        ) );
        $orders = $query->get_orders();
        $url = "https://api.sandbox.prodigi.com/v4.0/orders";
        $api_key = 'test_f47cb388-11bb-4453-b337-62574d0eae54';
        $response = wp_remote_post( $url, array(
            'method' => 'GET',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',
                'Top'=>100,
            
            ))  );
    
    
    $response_body = wp_remote_retrieve_body( $response );
    $response_body = json_decode( $response_body, true );
    $response_order = array_reverse( $response_body['orders']);
    var_dump($response_body['orders'][0]['shipments']);
   
    
    $prodigi_order_id_array = array();
    $prodigi_order_status_array = array();
    $woo_order_id_array = array();
    $shipment_array = array();
    
    foreach ($response_order as $key => $prodigi_order) {
        $prodigi_order_id = $prodigi_order['merchantReference'];
        $prodigi_order_status = $prodigi_order['status']['stage'];
        $shipment = $prodigi_order['shipments'];
        $shipment_array[] = $shipment;
        $prodigi_order_id_array[] = $prodigi_order_id;
        $prodigi_order_status_array[] = $prodigi_order_status;
        // var_dump($prodigi_order_status);
        
    }
    
    // var_dump($shipment_array);

        foreach($orders as $order_id){
            $order = wc_get_order($order_id);
            $status = $order->get_status();
            $woo_order_id = $order->get_id();
    
            if(in_array($woo_order_id, $prodigi_order_id_array)){
               if($prodigi_order_status == 'InProgress'){
                $order->update_status('wc-processing');
               }else if($prodigi_order_status == 'Complete'){
                $order->update_status('wc-completed');
               }else if($prodigi_order_status == 'Cancelled'){
                $order->update_status('wc-cancelled');
                }else{
                    $order->update_status('wc-on-hold');
                }
            }
        
            
        }
    }
}

