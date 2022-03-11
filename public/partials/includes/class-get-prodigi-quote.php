<?php
// add_action( 'woocommerce_after_checkout_form', 'get_prodigi_quote', 10 );
add_filter( 'woocommerce_package_rates', 'get_prodigi_quote', 50, 1 );

function get_prodigi_quote($rate) {

  global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id = $cart_item['product_id'];
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);

        var_dump($prodigiSKU);

        $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
        $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
        $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
        $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
        $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
        
        if($allow_live_mode == 'yes'){
            $url = $live_url;
            $api_key = $live_api_key;
        }else{
            $url = $test_url;
            $api_key = $test_api_key_vendor;
        }

 $quote_body = array (
    'shippingMethod' => 'Budget',
    'destinationCountryCode' => 'GB',
    'currencyCode' => 'GBP',
    'items' => 
    array (
      0 => 
      array (
        'sku' => 'GLOBAL-CAN-10x10',
        'copies' => 5,
        'attributes' => 
        array (
          'wrap' => 'ImageWrap',
        ),
        'assets' => 
        array (
          0 => 
          array (
            'printArea' => 'default',
          ),
        ),
      ),
      1 => 
      array (
        'sku' => 'GLOBAL-FAP-10x10',
        'copies' => 1,
        'attributes' => 
        array (
        ),
        'assets' => 
        array (
          0 => 
          array (
            'printArea' => 'default',
          ),
        ),
      ),
    ),
  );
                            //   End of Quote Body
 
                            //   Start of Quote Header
    $quote_content = array(
            'method' => 'POST',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',
            
            ), 
            'body' => json_encode(
                array (
                    'shippingMethod' => 'Budget',
                    'destinationCountryCode' => 'US',
                    'currencyCode' => 'USD',
                    'items' => 
                    array (
                      0 => 
                      array (
                        'sku' => $prodigiSKU,
                        'copies' => 5,
                        'attributes' => 
                        array (
                          'wrap' => 'ImageWrap',
                          'color' => 'Black',
                        ),
                        'assets' => 
                        array (
                          0 => 
                          array (
                            'printArea' => 'default',
                          ),
                        ),
                      ),
                    ),
                  )
            )
        );
        // echo $api_key;
        $response = wp_remote_post( 'https://api.sandbox.prodigi.com/v4.0/quotes', $quote_content );
        $response_body = wp_remote_retrieve_body( $response );
        var_dump($response_body);

                $new_cost = 1000;
            $tax_rate = 0.2;

            foreach( $rates as $rate_key => $rate ){
                // Excluding free shipping methods
                if( $rate->method_id != 'free_shipping'){

                    // Set rate cost
                    $rates[$rate_key]->cost = $new_cost;

                    // Set taxes rate cost (if enabled)
                    $taxes = array();
                    foreach ($rates[$rate_key]->taxes as $key => $tax){
                        if( $rates[$rate_key]->taxes[$key] > 0 )
                            $taxes[$key] = $new_cost * $tax_rate;
                    }
                    $rates[$rate_key]->taxes = $taxes;

                }
            }
            // return $rates;

       
 

    }

   

return $rates;

}

// add_filter( 'woocommerce_package_rates', 'custom_shipping_costs', 20, 2 );
// function custom_shipping_costs( $rates, $package ) {
//     // New shipping cost (can be calculated)
//     $new_cost = 1000;
//     $tax_rate = 0.2;

//     foreach( $rates as $rate_key => $rate ){
//         // Excluding free shipping methods
//         if( $rate->method_id != 'free_shipping'){

//             // Set rate cost
//             $rates[$rate_key]->cost = $new_cost;

//             // Set taxes rate cost (if enabled)
//             $taxes = array();
//             foreach ($rates[$rate_key]->taxes as $key => $tax){
//                 if( $rates[$rate_key]->taxes[$key] > 0 )
//                     $taxes[$key] = $new_cost * $tax_rate;
//             }
//             $rates[$rate_key]->taxes = $taxes;

//         }
//     }
//     return $rates;
// }