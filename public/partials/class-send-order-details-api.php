<?php

apply_filters( 'wcfm_products_additonal_data', '–', $product_id );
apply_filters( 'wcfm_products_additional_info_column_label', __( 'Additional Info', 'wc-frontend-manager' ) );
add_filter( 'wcfm_products_additonal_data_hidden', '__return_false' );
add_action('woocommerce_thankyou', 'send_order_to_prodigi');
// add_filter( 'woocommerce_default_address_fields', 'required_postcode_fields' );
// add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Remove the default post code field on cart page
function required_postcode_fields( $address_fields ) {
    unset( $address_fields['postcode'] );
    return $address_fields;
}

add_filter('woocommerce_shipping_calculator_enable_postcode', '__return_false' );




// add_action( 'woocommerce_checkout_process', 'my_custom_checkout_field_process' );


// function required_postcode_fields( $address_fields ) {
//     $address_fields['postcode']['required'] = true;
//     return $address_fields;
// }

// Send Order to Prodigy 

// $new_state = "";
// function translate_string(){
//   // global WC
  
//   $translated = WC()->countries->get_states( $country )[$state];
//   echo $translated;
// }

// translate_string();

function send_order_to_prodigi($order_id){

    $order = new WC_Order($order_id);
    $shipping = $order->shipping_address_1;
    $shipping_address_2 = $order->shipping_address_2;
    $city = $order->shipping_city;
    $state =  $order->shipping_state;
    $postcode =  $order->shipping_postcode;
    $country = $order->shipping_country;
    $shipping_state = WC()->countries->get_states( $country )[$state];
    $fullname = $order->billing_first_name ." ". $order->billing_last_name;
    $shipping_full_name = $order->shipping_first_name ." ". $order->shipping_last_name;
    $copies ;
    $shipping_method = WC()->session->get('shipping_price');
    $product_color = 'white';
    $product_wrap = '';
    $product_size = 'm';
    $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
    $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
    $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
    $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
    $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
    $trifie_cat_slug = array();
    $items = array();
    $translated = WC()->countries->get_states( $country )[$state];
    // $test_api_key = 'test_1ecc20d0-b515-456e-8583-79e0b320ebf2';

    // echo $state, $shipping_state;
    var_dump ($state);

    
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


    foreach ($order->get_items() as $item) {
        // Loop through each product in cart
        $product_id = $item->get_product_id();
        $product = wc_get_product($item->get_product_id());
        $attributes = $product->get_attributes();
        $item_sku = $product->get_sku();
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);
        $product_name = $item['name'];
        $printable_image_url = get_post_meta($product_id, '_printable_image', true);
        $copies = $item['quantity'];
        
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
      // $trifie_slug = $trifie_category[0]->slug;
      $slugs = $trifie_category[0]->slug;
      

      // Push the Trifie Category to an array
      // array_push($trifie_cat_slug, $trifie_category[0]->slug);
     
      // $trife_product_id = get_post_meta($pro_ID, 'trifie_product_id', true);
      // var_dump($trife_product_id);

        foreach ($item->get_meta_data() as $metaData) {
          $attribute = $metaData->get_data();
          $attribute_value = $attribute['value'];
          $attribute_key = $attribute['key'];
          var_dump($attribute_key);
          // array_push($product_ui, $attribute_key);
          if($attribute_key == 'color'){
            $product_color = $attribute_value;
          }//end if
          if($attribute_key == 'frame-color'){
            $product_color = $attribute_value;
          }
          if($attribute_key== 'wrap'){
            $product_wrap = $attribute_value;
          }
          if($attribute_key == 'size'){
            $product_size = $attribute_value;
          }//end if

        }//end Meta foreach

        // var_dump($trifie_cat_slug);
        // Push Category to a unique array
        
        // var_dump($slugs);




        $items_generic = array(
            'merchantReference' => $item_sku. '-'. $product_name . '-'. $trifie_cat_slug,
            'sku' => $prodigiSKU,
            'copies'=> $copies,
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

        );
        $items_framed = array(
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'attributes' => 
          array (
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
        );
        $items_framed_canvas = array(
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'attributes' => 
          array (
            'color'=>$product_color,
            'wrap'=>$product_wrap ? $product_wrap: 'white'
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
        );
        $items_postcard = array(
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU ? '': $item_sku,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'assets' => 
          array (
            0 => 
            array (
              'url' => $printable_image_url,
              'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
            ),
          ),
        );
       
        $items_apparel = array(
          'merchantReference' => $item_sku. '-'. $product_name ,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'attributes' => 
            array (
              'size' =>$product_size ? $product_size : 'm',
              'color'=>$product_color ? $product_color : 'black',
    
            ),
          'assets' => 
          array (
            0 => 
            array (
              'printArea'=>'default',
              'url' => $printable_image_url,
              'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
            ),
          ),
        );

        $items_socks = array(
          'merchantReference' => $item_sku. '-'. $product_name ,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'attributes' => 
            array (
              'size' =>$product_size ? $product_size : 'm',
    
            ),
          'assets' => 
          array (
            0 => 
            array (
              'printArea'=>'default',
              'url' => $printable_image_url,
              'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
            ),
          ),
        );

        $items_patch_round = array(
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
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
        );
        $items_prints = array(
          'merchantReference' => $item_sku. '-'. $product_name,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
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
        );
        $items_photo_tiles=array(
          'merchantReference' => $item_sku. '-'. $product_name ,
          'sku' => $prodigiSKU,
          'copies'=> $copies,
          'sizing' => 'fillPrintArea',
          'attributes' => 
            array (
              'color' =>$product_color ? $product_color : 'black',
    
            ),
          'assets' => 
          array (
            0 => 
            array (
              'printArea'=>'default',
              'url' => $printable_image_url,
              'md5Hash' => 'dcb2b27755a6f2ceb09089856508f31b',
            ),
          ),
        );
        var_dump($slugs);
       if($slugs == 'framed'){
          array_push($items, $items_framed);
        
        }elseif($slugs == 'framed-canvas'){
          array_push($items, $items_framed_canvas);
        }
        elseif($slugs == 'postcard'){
          array_push($items, $items_postcard);
        }elseif($slugs == 'apparel'){
          array_push($items, $items_apparel);
        }elseif($slugs == 'patch-round'){
          array_push($items, $items_patch_round);
        }elseif($slugs == 'prints'){
          array_push($items, $items_prints);
        }
        elseif($slugs == 'socks'){
          array_push($items, $items_socks);
          

        } elseif($slugs == 'photo-tiles'){
          array_push($items, $items_photo_tiles);
        

        }else{
          array_push($items, $items_generic);
        }//end if
        

// end of foreach

      }

  
      
  

    // For framed canvas Category
    $framed_canvas = json_encode(
        array (
            'merchantReference' => $order_id,
            'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
            'recipient' => 
            array (
              'address' => 
              array ( 
                'line1' => $shipping,
                'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
                'postalOrZipCode' => $postcode ?$postcode: '09029',
                'countryCode' => $country,
                'townOrCity' =>$city,
                'stateOrCounty' => $shipping_state,
              ),
              'name' => $shipping_full_name ? $shipping_full_name : $fullname,
            ),
            'items' => $items,
            
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
          'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
          'recipient' => 
          array (
            'address' => 
            array (
              'line1' => $shipping,
              'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
              'postalOrZipCode' => $postcode ?$postcode: '09029',
              'countryCode' => $country,
              'townOrCity' =>$city,
              'stateOrCounty' => $shipping_state,
            ),
            'name' => $shipping_full_name ? $shipping_full_name : $fullname,
          ),
           'items' => $items,
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
          'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
          'recipient' => 
          array (
            'address' => 
            array (
              'line1' => $shipping,
              'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
              'postalOrZipCode' => $postcode ?$postcode: '09029',
              'countryCode' => $country,
              'townOrCity' =>$city,
              'stateOrCounty' => $shipping_state,
            ),
            'name' => $shipping_full_name ? $shipping_full_name : $fullname,
          ),
          'items' => $items,
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
        'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
        'recipient' => 
        array (
          'address' => 
          array (
            'line1' => $shipping,
            'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
            'postalOrZipCode' => $postcode ?$postcode: '09029',
            'countryCode' => $country,
            'townOrCity' =>$city,
            'stateOrCounty' => $shipping_state,
          ),
          'name' => $shipping_full_name ? $shipping_full_name : $fullname,
        ),
        'items' => $items,
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

// For Prints Category
$prints = json_encode(
  array (
      'merchantReference' => $order_id,
      'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
      'recipient' => 
      array (
        'address' => 
        array (
          'line1' => $shipping,
          'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
          'postalOrZipCode' => $postcode ?$postcode: '09029',
          'countryCode' => $country,
          'townOrCity' =>$city,
          'stateOrCounty' => $shipping_state,
        ),
        'name' => $shipping_full_name ? $shipping_full_name : $fullname,
      ),
      'items' => $items,
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
      'shippingMethod' => $shipping_method ? $shipping_method : 'budget',
      'recipient' => 
      array (
        'address' => 
        array (
          'line1' => $shipping,
          'line2' => $shipping_address_2 ? $shipping_address_2 : $shipping,
          'postalOrZipCode' => $postcode ?$postcode: '09029',
          'countryCode' => $country,
          'townOrCity' =>$city,
          'stateOrCounty' => $shipping_state,
        ),
        'name' => $shipping_full_name ? $shipping_full_name : $fullname,
      ),
      'items' => $items,
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

$print_args= array(
  'method' => 'POST',
  'headers' => array(
      'X-API-Key' => $api_key,
      'Content-type' => 'application/json',
      'Accept' => 'application/json; charset=utf-8',
  ),
  'body' => $prints
);


$response = wp_remote_request( $url.'Orders/', $generig_args);  
// var_dump($copies);
var_dump($response['body']);

    if($response['response']['code'] != 200){
      
      echo'
      <h2 style="color:red; font-size:20px">Error '.$response['response']['code'].'! Sorry, we could not send your order to Prodigi for printing. Please contact site admin</h2>';
    }else{
      $order->update_meta_data( '_prodigi_shipping_method',   $shipping_method );
      $order->save();
      echo _e( '<h2 style="color:green; font-size:20px"> Your order has been sent to Prodigi for printing. </h2>', 'trifie');

    }
// }


    // $bd = json_decode($response_body, true);
    // var_dump($bd['order']['shipments']);
 
}

