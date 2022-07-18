<?php

wp_enqueue_script(  'jquery' );

add_filter('woocommerce_before_checkout_billing_form', 'woo_billing_field');
add_action('woocommerce_before_shipping_calculator', 'prodigi_shipping_cart');

function prodigi_shipping_cart(){
  $customer = WC()->session->get('customer');
  //  var_dump($customer['shipping_country']);
    echo ' <p class="shipping-error"></p>';
    woocommerce_form_field('prodigi_shipping_cart', array(
        'type' => 'select',
        'options' => array(
            'budget' => 'Budget (No Tracking number)',
            'standard' => 'Standard (with tracking number)',
            'express' => 'Express (tracking number + express delivery)',
        ),
        'label' => __('Select Shipping Method'),
        'required' => true,
        'class' => array('form-row-wide'),
        'label_class' => array('form-row-wide'),
    ),  WC()->session->get('shipping_price'));
}

function woo_billing_field(){
  // var_dump(WC()->cart->show_shipping());
   echo ' <p class="shipping-error"></p>';
   woocommerce_form_field('prodigi_shipping', array(
       'type' => 'select',
       'options' => array(
           'budget' => 'Budget (No Tracking number)',
           'standard' => 'Standard (with tracking number)',
           'express' => 'Express (tracking number + express delivery)',
       ),
       'class' => array('custom-shipping', 'form-row-wide'),
       'label' => __('Shipping Method', 'woocommerce'),
       'required' => true,
       'label_class' => array('form-row-wide'),
   ),  WC()->session->get('shipping_price'));
}


// function custom_woocommerce_billing_fields($fields)
// {
//   var_dump(WC()->session->get('shipping_price'));

//     $fields['prodigi_shipping'] = array(
//         'label' => __('Shipping Method', 'woocommerce'), // Add custom field label
//         'required' => true, // if field is required or not
//         'clear' => false, // add clear or not
//         'type' => 'select', // add field type
//         'options' => array(
//             'budget' => __('Budget', 'woocommerce'),
//             'standard' => __('Standard', 'woocommerce'),
//             'express' => __('Express', 'woocommerce'),
//         ),
//         'class' => array('custom-shipping'),    // add class name
        
        
//     );


//     return $fields;
// }

// add_filter( 'default_checkout_billing_country', 'change_default_checkout_country', 10, 1 );

// function change_default_checkout_country( $country ) {
//     // If the user already exists, don't override country
//     // if ( WC()->customer->get_is_paying_customer() ) {
//       // $customer = WC()->session->get('customer');
//       // var_dump($customer['shipping_country']);
//         // return $customer['shipping_country'];
//         return 'US';

// }


// add_filter( 'woocommerce_checkout_fields', 'prodigi_email_reorder' );

function prodigi_email_reorder( $checkout_fields ) {
	$checkout_fields['billing']['prodigi_shipping']['priority'] = 4;
	return $checkout_fields;
}


add_action('wp_ajax_get_prodigi_quote', 'get_prodigi_quote');
add_action('wp_ajax_nopriv_prodigi_quote', 'get_prodigi_quote');

add_action('wp_ajax_get_prodigi_quote_price', 'get_prodigi_quote_price');
add_action('wp_ajax_nopriv_get_prodigi_quote_price', 'get_prodigi_quote_price');

function get_prodigi_quote() {

  global $woocommerce;

    $items = $woocommerce->cart->get_cart();

    if ( isset($_POST['shipping_price']) ) {
        WC()->session->set('shipping_price', ($_POST['shipping_price'] ) );
        // wp_send_json( WC()->session->get('shipping_price'));
        // die;
    }

    
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id = $cart_item['product_id'];
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);

        // var_dump($prodigiSKU);

        $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
        $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
        $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
        $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
        $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
        $current_post = get_posts(array('post_type' => 'trifie_sku', 'meta_key' => 'prodigi_trifie_sku', 'meta_value' => $prodigiSKU));
        $current_trifie_category = get_the_terms($current_post[0]->ID, 'trifie_sku_category');
        $current_trifie_category_slug = $current_trifie_category[0]->slug;

                
        if($allow_live_mode == 'yes'){
            $url = $live_url;
            $api_key = $live_api_key;
        }else{
            $url = $test_url;
            $api_key = $test_api_key_vendor;
        }


        $custome_shipping_country = WC()->customer->get_shipping_country();
        $shipping_price = WC()->session->get('shipping_price');
        $copies = $woocommerce->cart->get_cart_item_quantities();
        // var_dump($copies);
    
        // For patchrounds
    $patch_round_array = json_encode( array (
            'shippingMethod' => $shipping_price,
            'destinationCountryCode' => $custome_shipping_country,
            'currencyCode' => 'USD',
            'items' => 
            array (
            0 => 
            array (
                'sku' => $prodigiSKU,
                'copies' => 1,
                'assets' => 
                array (
                0 => 
                array (
                    'printArea' => 'default',
                ),
                ),
            ),
            ),
        ));
    // For Generics
    $unknown_array = json_encode( array  (
      'shippingMethod' => $shipping_price,
      'destinationCountryCode' => $custome_shipping_country,
      'currencyCode' => 'USD',
      'items' => 
      array (
        0 => 
        array (
          'sku' => $prodigiSKU,
          'copies' =>1,
          'assets' => 
          array (
            0 => 
            array (
              'printArea' => 'default',
            ),
          ),
        ),
      ),
    ));
    // For Footware
    $footware_array = json_encode( array (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));
      
    //   For Tech
    $tech_array = json_encode( array (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));
    //    For Stationery
    $stationery_array  = json_encode( array (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));

   
      $apparels_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'attributes' => 
            array (
              'color' => 'white',
              'size' => 'm',
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
      ));

      $framed_canvas_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'attributes' => 
            array (
              'color' => 'white',
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
        ),
      ));

      $rolled_canvas_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            // 'attributes' => 
            // array (
            //   'wrap' => 'ImageWrap',
            // ),
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));

      $stretched_canvas_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'attributes' => 
            array (
                'color' => 'black',
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
        ),
      ));

      $framed_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'attributes' => 
            array (
                'color' => 'black',
                // 'wrap' => 'ImageWrap',
            
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
      ));
    //   To be checked
    

      $home_ware_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            // 'attributes' => 
            // array (
            //     'color' => 'black',
            //     'wrap' => 'ImageWrap',
            
            // ),
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));
      $mounted_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));

      $photo_gift_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            // 'attributes' => 
            // array (
            //     'color' => 'black',
            //     'wrap' => 'ImageWrap',
            
            // ),
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));

      $prints_gift_array = json_encode( array  (
        'shippingMethod' => $shipping_price,
        'destinationCountryCode' => $custome_shipping_country,
        'currencyCode' => 'USD',
        'items' => 
        array (
          0 => 
          array (
            'sku' => $prodigiSKU,
            'copies' => 1,
            'assets' => 
            array (
              0 => 
              array (
                'printArea' => 'default',
              ),
            ),
          ),
        ),
      ));

      $body_array;
      if($current_trifie_category_slug == 'patch-round'){
        $body_array = $patch_round_array;
        }elseif($current_trifie_category_slug == 'gallery'){
        $body_array = $galerry_array;
        }elseif($current_trifie_category_slug == 'home-ware'){
        $body_array = $home_ware_array;
        }elseif($current_trifie_category_slug == 'mounted'){
        $body_array = $mounted_array;
        }elseif($current_trifie_category_slug == 'photo-gift'){
        $body_array = $photo_gift_array;
        }elseif($current_trifie_category_slug == 'prints-gift'){
        $body_array = $prints_gift_array;
        }elseif($current_trifie_category_slug == 'framed'){
        $body_array = $framed_array;
        }elseif($current_trifie_category_slug == 'stretched-canvas'){
        $body_array = $stretched_canvas_array;
        }elseif($current_trifie_category_slug == 'apparel'){
        $body_array = $apparels_array;
        }else{
        $body_array = $unknown_array;
        }
   



    $quote_content = array(
            'method' => 'POST',
            'headers' => array(
                'X-API-Key' => $api_key,
                'Content-type' => 'application/json',
                'Accept' => 'application/json; charset=utf-8',
            
            ), 
            'body' => $body_array,
            // end of body
        );
        // echo $api_key;
        $response = wp_remote_post( $url.'/quotes', $quote_content );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
		if($response_body['outcome']=='NotAvailable'){

      // $shippingCost = array( $response_body, $api_key);
      // var_dump($shippingCost);

      echo __('<div class="alert alert-danger" role="alert"> <p> Sorry, this product is not available in your country </p>. </div>', 'trifie');
			// echo '<p style="color:red; text-align:center;">Product Not Available in your country</p>';
		}else{
        // $shippingCost = array( $response_body, $api_key);
			 $shippingCost = intval($response_body['quotes'][0]['costSummary']['shipping']['amount']);
		}
       
	// $shippingCost = intval($response_body['quotes'][0]['costSummary']['shipping']['amount']);
    }
    
    // var_dump( json_decode ($body_array, true) );
    // wp_send_json($response_body);
    // wp_die();
    // var_dump($copies);
      
    return $shippingCost;

}

function save_custom_data( $cart_item_data ) {

    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_data', 10 );




add_action( 'woocommerce_cart_calculate_fees', 'custom_fee_based_on_cart_total', 10, 1 );

function custom_fee_based_on_cart_total( $cart ) {

    // if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    // The conditional Calculation
    $fee = get_prodigi_quote();
    // $fee = 0;

    // if ( $fee != 0 ) 
    // $shipping_method = WC()->session->get('shipping_price');

      // if ($fee == 0){
      //   echo'
      //     <h3 style="color:red"> The selected shipping ' .$shipping_method.  ' method is not available in your location</h3>
      //   ';
      // }
        $cart->add_fee( __( "Shipping Cost", "woocommerce" ), $fee, false );
        
}





// function disable_shipping_calc_on_cart( $show_shipping ) {
//     if( is_cart() ) {
//         return false;
//     }
//     else {
//         return false;
//     }
//     return $show_shipping;
// }
// add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );


add_action('woocommerce_checkout_create_order', 'update_custom_order_fields', 20, 2);
function update_custom_order_fields( $order, $data ) {
    // Update shipping method
    // update shipping cost
    // update Shipping country

    $shipping_method = WC()->session->get('shipping_price');

    $order->update_meta_data( '_prodigi_shipping_method', $shipping_method );
}

add_action( 'woocommerce_thankyou', 'view_prodigi_shipping_details', 10 );
add_action( 'woocommerce_view_order', 'view_prodigi_shipping_details', 10 );

function view_prodigi_shipping_details( $order_id ){  ?>
    <h2>Delivery Details</h2>
    <table class="woocommerce-table shop_table gift_info">
        <tbody>
            <tr>
                <th>Shipping Method</th>
                <td ><strong> <?php echo ucfirst( get_post_meta( $order_id, '_prodigi_shipping_method', true )); ?></strong></td>
            </tr>
        </tbody>
    </table>
<?php }


function get_prodigi_quote_price(){
  wp_send_json(get_prodigi_quote());
}