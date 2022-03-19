<?php
if (strpos($_SERVER['REQUEST_URI'], "orderslist") !== false) {
    // echo "now on order page";


    $url = "https://api.sandbox.prodigi.com/v4.0/orders";
    $api_key = 'test_f47cb388-11bb-4453-b337-62574d0eae54';

    // $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
    // $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
    // $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
    // $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
    // $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
    
    // if($allow_live_mode == 'yes'){
    //     $url = $live_url;
    //     $api_key = $live_api_key;
    // }else{
    //     $url = $test_url;
    //     $api_key = $test_api_key_vendor;
    // }

 

    $response = wp_remote_post( $url, array(
            'method' => 'GET',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',
                'top'=>100,
            
            ))  );

    
    $response_body = wp_remote_retrieve_body( $response );
    $response_body = json_decode( $response_body, true );
    $resonse_order = $response_body['orders'];
    // var_dump($response_body);




  
}

