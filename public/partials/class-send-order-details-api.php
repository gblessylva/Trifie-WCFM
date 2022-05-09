<?php

apply_filters( 'wcfm_products_additonal_data', '–', $product_id );
apply_filters( 'wcfm_products_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) );
add_filter( 'wcfm_products_additonal_data_hidden', '__return_false' );
add_action('woocommerce_thankyou', 'send_order_to_prodigi');
add_filter( 'woocommerce_default_address_fields', 'required_postcode_fields' );

function required_postcode_fields( $address_fields ) {
    $address_fields['postcode']['required'] = true;
    return $address_fields;
}

// Send Order to Prodigy 

function send_order_to_prodigi($order_id){

    $order = new WC_Order($order_id);
    $shipping = $order->shipping_address_1;
    $city = $order->shipping_city;
    $state =  $order->shipping_state;
    $postcode =  $order->shipping_postcode;
    $country = $order->shipping_country;
    $fullname = $order->billing_first_name ." ". $order->billing_last_name;
    $copies = $order->get_item_count();
    $shipping_method = WC()->session->get('shipping_price');
    $product_color = 'white';
    $product_size = 'm';
    foreach ($order->get_items() as $item) {
        $product = wc_get_product($item->get_product_id());
        $attributes = $product->get_attributes();
        $item_sku = $product->get_sku();
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);
        $product_name = $item['name'];
        $product_id = $item->get_product_id();
        $printable_image_url = get_post_meta($product_id, '_printable_image', true);
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);
        foreach ($item->get_meta_data() as $metaData) {
          $attribute = $metaData->get_data();
          $attribute_value = $attribute['value'];
          $attribute_key = $attribute['key'];
          if($attribute_key == 'pa_color'){
            $product_color = $attribute_value;
          }
          if($attribute_key == 'pa_size'){
            $product_size = $attribute_value;
          }
        }
        // var_dump($product_color, $product_size);

        // Get the current Trifie Product
        $args = array(
          'post_type' => 'trifie_sku',
          'meta_query' => array(
              'relation' => 'AND',
              array(
                  'key' => 'prodigi_trifie_sku',
                  'value' =>$prodigiSKU,
                  // 'compare' => '=',
              ),
          ), 
      );
      
      $the_query = get_posts($args);
      $pro_ID =$the_query[0]->ID;
      $trifie_category = get_the_terms( $pro_ID, 'trifie_sku_category' );
      $trifie_cat_slug = $trifie_category[0]->slug;
      // var_dump($trifie_cat_slug);
      // $trife_product_id = get_post_meta($pro_ID, 'trifie_product_id', true);
      // var_dump($trife_product_id);

      }
    $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
    $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
    $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
    $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
    $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
    // $test_api_key = 'test_1ecc20d0-b515-456e-8583-79e0b320ebf2';

    // echo $test_url;
    $url = '';
    $api_key = '';
    if($allow_live_mode == 'yes'){
        $url = $live_url;
        $api_key = $live_api_key;
        echo "now Life" . $url, $api_key;
    }else{
        $url = $test_url;
        $api_key = $test_api_key_vendor;
        echo 'Still testing', $url, $api_key;
    }

    // For framed canvas Category
    $framed_canvas = json_encode(
        array (
            'merchantReference' => $order_id,
            'shippingMethod' => $shipping_method,
            'recipient' => 
            array (
              'address' => 
              array (
                'line1' => $shipping,
                'line2' => $shipping,
                'postalOrZipCode' => $postcode ?$postcode: '09029',
                'countryCode' => $country,
                'townOrCity' =>$city,
                'stateOrCounty' => $state,
              ),
              'name' => $fullname,
            ),
            'items' => 
            array (
              0 => 
              array (
                'merchantReference' => $item_sku. '-'. $product_name,
                'sku' => $prodigiSKU,
                'copies' => $copies,
                'sizing' => 'fillPrintArea',
                'attributes' => 
                array (
                  // 'size' => 's',
                  'color'=>$product_color,
                  // 'wrap' => 'White'
                ),
                'assets' => 
                array (
                  0 => 
                  array (
                    'printArea' => 'Default',
                    'url' => $printable_image_url,
                    'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
                  ),
                ),
              ),
            ),
            'metadata' => 
            array (
              'mycustomkey' => 'some-guid',
              'someCustomerPreference' => 
              array (
                'preference1' => 'something',
                'preference2' => 'red',
              ),
              'sourceId' => 12345,
            ),
          )
    );

    $patch_round_array =  json_encode(
      array (
          'merchantReference' => $order_id,
          'shippingMethod' => $shipping_method,
          'recipient' => 
          array (
            'address' => 
            array (
              'line1' => $shipping,
              'line2' => $shipping,
              'postalOrZipCode' => $postcode ?$postcode: '09029',
              'countryCode' => $country,
              'townOrCity' =>$city,
              'stateOrCounty' => $state,
            ),
            'name' => $fullname,
          ),
          'items' => 
          array (
            0 => 
            array (
              'merchantReference' => $item_sku. '-'. $product_name,
              'sku' => $prodigiSKU,
              'copies' => $copies,
              'sizing' => 'fillPrintArea',
              'attributes' => 
              array (
                'size' => $product_size,
                'color'=>$product_color,
              ),
              'assets' => 
              array (
                0 => 
                array (
                  'printArea' => 'Default',
                  'url' => $printable_image_url,
                  'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
                ),
              ),
            ),
          ),
          'metadata' => 
          array (
            'mycustomkey' => 'some-guid',
            'someCustomerPreference' => 
            array (
              'preference1' => 'something',
              'preference2' => 'red',
            ),
            'sourceId' => 12345,
          ),
        )
  );
  
    // POst Card
    $post_card = json_encode(
      array (
          'merchantReference' => $order_id,
          'shippingMethod' => $shipping_method,
          'recipient' => 
          array (
            'address' => 
            array (
              'line1' => $shipping,
              'line2' => $shipping,
              'postalOrZipCode' => $postcode ?$postcode: '09029',
              'countryCode' => $country,
              'townOrCity' =>$city,
              'stateOrCounty' => $state,
            ),
            'name' => $fullname,
          ),
          'items' => 
          array (
            0 => 
            array (
              'merchantReference' => $item_sku. '-'. $product_name,
              'sku' => $prodigiSKU,
              'copies' => $copies,
              'sizing' => 'fillPrintArea',
              'assets' => 
              array (
                0 => 
                array (
                  'printArea' => 'Default',
                  'url' => $printable_image_url,
                  'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
                ),
              ),
            ),
          ),
          'metadata' => 
          array (
            'mycustomkey' => 'some-guid',
            'someCustomerPreference' => 
            array (
              'preference1' => 'something',
              'preference2' => 'red',
            ),
            'sourceId' => 12345,
          ),
        )
  );

  $apparel = json_encode(
    array (
        'merchantReference' => $order_id,
        'shippingMethod' => $shipping_method,
        'recipient' => 
        array (
          'address' => 
          array (
            'line1' => $shipping,
            'line2' => $shipping,
            'postalOrZipCode' => $postcode ?$postcode: '09029',
            'countryCode' => $country,
            'townOrCity' =>$city,
            'stateOrCounty' => $state,
          ),
          'name' => $fullname,
        ),
        'items' => 
        array (
          0 => 
          array (
            'merchantReference' => $item_sku. '-'. $product_name,
            'sku' => $prodigiSKU,
            'copies' => $copies,
            'sizing' => 'fillPrintArea',
            'attributes' => 
            array (
              'size' => $product_size,
              'color'=>$product_color,
            ),
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'Default',
                'url' => $printable_image_url,
                'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
              ),
            ),
          ),
        ),
        'metadata' => 
        array (
          'mycustomkey' => 'some-guid',
          'someCustomerPreference' => 
          array (
            'preference1' => 'something',
            'preference2' => 'red',
          ),
          'sourceId' => 12345,
        ),
      )
);

$generic= json_encode(
  array (
      'merchantReference' => $order_id,
      'shippingMethod' => $shipping_method,
      'recipient' => 
      array (
        'address' => 
        array (
          'line1' => $shipping,
          'line2' => $shipping,
          'postalOrZipCode' => $postcode ?$postcode: '09029',
          'countryCode' => $country,
          'townOrCity' =>$city,
          'stateOrCounty' => $state,
        ),
        'name' => $fullname,
      ),
      'items' => 
      array (
        0 => 
        array (
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU,
          'copies' => $copies,
          'sizing' => 'fillPrintArea',
          'assets' => 
          array (
            0 => 
            array (
              'printArea' => 'Default',
              'url' => $printable_image_url,
              'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
            ),
          ),
        ),
      ),
      'metadata' => 
      array (
        'mycustomkey' => 'some-guid',
        'someCustomerPreference' => 
        array (
          'preference1' => 'something',
          'preference2' => 'red',
        ),
        'sourceId' => 12345,
      ),
    )
);



    $framed_args = array(
        'method' => 'POST',
        'headers' => array(
            'X-API-Key' => $api_key,
            'Content-type' => 'application/json',
            'Accept' => 'application/json; charset=utf-8',
        
        ), 
        'body' => $framed_canvas
    );
    $postcard_args = array(
      'method' => 'POST',
      'headers' => array(
          'X-API-Key' => $api_key,
          'Content-type' => 'application/json',
          'Accept' => 'application/json; charset=utf-8',
      
      ), 
      'body' => $post_card
  );
  $patch_round_args = array(
    'method' => 'POST',
    'headers' => array(
        'X-API-Key' => $api_key,
        'Content-type' => 'application/json',
        'Accept' => 'application/json; charset=utf-8',
    
    ), 
    'body' => $patch_round_array
  );

  $apparel_args= array(
    'method' => 'POST',
    'headers' => array(
        'X-API-Key' => $api_key,
        'Content-type' => 'application/json',
        'Accept' => 'application/json; charset=utf-8',
    
    ), 
    'body' => $apparel
);

$generig_args= array(
  'method' => 'POST',
  'headers' => array(
      'X-API-Key' => $api_key,
      'Content-type' => 'application/json',
      'Accept' => 'application/json; charset=utf-8',
  ),
  'body' => $generic
);

$response = wp_remote_request( $url.'Orders/', $framed_args );
    
    if($trifie_cat_slug == 'postcard'){
      $response = wp_remote_request( $url.'Orders/', $postcard_args );
    }elseif ($trifie_cat_slug == 'framed-canvas') {
      $response = wp_remote_request( $url.'Orders/', $framed_args );
    }elseif($trifie_cat_slug=='apparel'){
      $response = wp_remote_request( $url.'Orders/', $apparel_args );
    }elseif($trifie_cat_slug=='patch-round'){
      $response = wp_remote_request( $url.'Orders/', $patch_round_args );
    }elseif ($trifie_cat_slug == 'framed') {
      $response = wp_remote_request( $url.'Orders/', $framed_args );
    }else{
      $response = wp_remote_request( $url.'Orders/', $generig_args );
    }

      
    $response_body = wp_remote_retrieve_body( $response ); 
    // var_dump($response['body']);
    // var_dump ($trifie_cat_slug);

    if($response['response']['code'] != 200){
      echo'
      <h2 style="color:red; font-size:20px">Error '.$response['response']['code'].'! Sorry, we could not send your order to Prodigi for printing. Please contact site admin</h2>';
    }else{
      echo _e( '<h2 style="color:green; font-size:20px"> Your order has been sent to Prodigi for printing. </h2>', 'trifie');

    }
    // $bd = json_decode($response_body, true);
    // var_dump($bd['order']['shipments']);
 
}

