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
    $product_array = array();

    if ( isset($_POST['shipping_price']) ) {
        WC()->session->set('shipping_price', ($_POST['shipping_price'] ) );
    
    }
    $allow_live_mode = wcfm_get_option('wcfm_prodigy_live_mode');
    $test_url = wcfm_get_option('wcfm_prodigy_test_api_url');
    $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', '');
    $live_api_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
    $test_api_key_vendor =wcfm_get_option('wcfm_prodigy_test_api_key', '');
    $shipping_price = WC()->session->get('shipping_price');
    // var_dump($shipping_price);
    $url = '';
    $api_key = '';
    $product_array = array();

    // Check if Application is LIVE or TEST
    if ($allow_live_mode == 'yes') {
        $url = $live_url;
        $api_key = $live_api_key;
    } else {
        $url = $test_url;
        $api_key = $test_api_key_vendor;
    }

    // Loop through cart items

    foreach ($items as $item => $values) {
      $product_id = $values['product_id'];
      $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);
      $current_post = get_posts(array('post_type' => 'trifie_sku', 'meta_key' => 'prodigi_trifie_sku', 'meta_value' => $prodigiSKU));
      $current_trifie_category = get_the_terms($current_post[0]->ID, 'trifie_sku_category');
      $current_trifie_category_slug = $current_trifie_category[0]->slug;
      $custome_shipping_country = WC()->customer->get_shipping_country();
      $copies = $woocommerce->cart->get_cart_item_quantities();

      // Check Product Category SLug
      
      $items_generic = array(
        'sku' => $prodigiSKU,
        'copies'=> 1,
        'assets' => 
        array (
          0 => 
          array (
            'printArea' => 'Default',
          ),
        ),

    );
    $items_framed = array(
      'sku' => $prodigiSKU,
      'copies'=> 1,
      'attributes' => 
      array (
        'color'=>$product_color ? $product_color : 'black',
      ),
      'assets' => 
      array (
        0 => 
        array (
          'printArea' => 'Default',          
        ),
      ),
    );
    $items_postcard = array(
      
      'sku' => $prodigiSKU ? '': $item_sku,
      'copies'=> 1,
     
      'assets' => 
      array (
        0 => 
        array (
         
          
        ),
      ),
    );
    $items_apparel = array(
      'sku' => $prodigiSKU,
      'copies'=> 1,
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

        ),
      ),
    );
    $items_patch_round = array(
      
      'sku' => $prodigiSKU,
      'copies'=> 1,
     
      'assets' => 
      array (
        0 => 
        array (
          'printArea' => 'Default',
         
          
        ),
      ),
    );
    $items_prints = array(
      
      'sku' => $prodigiSKU,
      'copies'=> 1,
     
      'assets' => 
      array (
        0 => 
        array (
          'printArea' => 'Default',
         
          
        ),
      ),
    );
    $items_framed_canvas = array(
      'sku' => $prodigiSKU,
      'copies'=> 1,
      'attributes' => 
      array (
        'color'=>$product_color ? $product_color : 'black',
        'wrap'=>'imageWrap',
      ),
      'assets' => 
      array (
        0 => 
        array (
          'printArea' => 'Default',
         
          
        ),
      ),
    );
    // Compare Category Slug and assign array
    if($current_trifie_category_slug == 'generic'){
      array_push($product_array, $items_generic);
    }elseif($current_trifie_category_slug == 'framed'){
      array_push($product_array, $items_framed);
    }elseif($current_trifie_category_slug == 'postcard'){
      array_push($product_array, $items_postcard);
    }elseif($current_trifie_category_slug == 'apparel'){
      array_push($product_array, $items_apparel);
    }elseif($current_trifie_category_slug == 'patch_round'){
     array_push($product_array, $items_patch_round);
    }elseif($current_trifie_category_slug == 'prints'){
     array_push($product_array, $items_prints);
    }elseif($current_trifie_category_slug == 'framed-canvas'){
     array_push($product_array, $items_framed_canvas);
    }else{
      array_push($product_array, $items_generic);

    }
  }
  // End of Loop

  // $production = array(
  //   array =
  // );

  $prodigi_items_array = json_encode( array  (
    'shippingMethod' => $shipping_price? $shipping_price : 'Budget',
    'destinationCountryCode' => $custome_shipping_country,
    'currencyCode' => 'USD',
    // 'items' => $productuction,
    'items' =>  $product_array,
  ));


 
  $quote_content = array(
    'method' => 'POST',
    'headers' => array(
        'X-API-Key' => $api_key,
        'Content-type' => 'application/json',
        'Accept' => 'application/json; charset=utf-8',
    
    ), 
    'body' => $prodigi_items_array,
    // end of body
);
  $response = wp_remote_post( $url.'/quotes', $quote_content );
  $response_body = wp_remote_retrieve_body( $response );
  $response_body = json_decode( $response_body, true );
  $shippingCost = intval($response_body['quotes'][0]['costSummary']['shipping']['amount']);
 

  return $shippingCost;


  }

  add_action('woocommerce_before_cart', 'get_prodigi_quote');


  function save_custom_data( $cart_item_data ) {

    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_data', 10 );

add_action( 'woocommerce_cart_calculate_fees', 'custom_fee_based_on_cart_total', 10, 1 );

function custom_fee_based_on_cart_total( $cart ) {
    // The conditional Calculation
    $fee = get_prodigi_quote();
        $cart->add_fee( __( "Shipping Cost", "woocommerce" ), $fee, false );
        
}


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
  // var_dump( get_prodigi_quote() );
  // echo '<h1>Quotes</h1>';
  // get_prodigi_quote();
  wp_send_json(get_prodigi_quote());
}

// add_action('woocommerce_before_cart', 'get_prodigi_quote_price');