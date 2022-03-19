<?php
// add_action( 'woocommerce_after_checkout_form', 'get_prodigi_quote', 10 );
// add_filter( 'woocommerce_package_rates', 'get_prodigi_quote', 50, 1 );

// function update_prodigi_shiping(){
   
    
    
// }
add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
{

    $fields['prodigi_shipping'] = array(
        'label' => __('Shipping Method', 'woocommerce'), // Add custom field label
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'select', // add field type
        'options' => array(
            'budget' => __('Budget', 'woocommerce'),
            'standard' => __('Standard', 'woocommerce'),
            'express' => __('Express', 'woocommerce'),
        ),
        'class' => array('custom-shipping')    // add class name
    );

    return $fields;
}



add_action('wp_ajax_get_prodigi_quote', 'get_prodigi_quote');



function get_prodigi_quote() {

  global $woocommerce;

    $items = $woocommerce->cart->get_cart();
    if ( isset($_POST['shipping_price']) ) {
        WC()->session->set('shipping_price', ($_POST['shipping_price'] ) );
        echo  WC()->session->get('shipping_price');
        die();
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

        
        if($allow_live_mode == 'yes'){
            $url = $live_url;
            $api_key = $live_api_key;
        }else{
            $url = $test_url;
            $api_key = $test_api_key_vendor;
        }


        $custome_shipping_country = WC()->customer->get_shipping_country();
        // var_dump ($custome_shipping_country);\
        // $get_prodigi_shipping_cost = update_prodigi_shiping();
        // var_dump($get_prodigi_shipping_cost);
        // var_dump($area);

        $shipping_price = WC()->session->get('shipping_price');
        // return $shipping_price;

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
                    'shippingMethod' => $shipping_price,
                    'destinationCountryCode' => $custome_shipping_country,
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
        $response_body = json_decode( $response_body, true );
        $shippingCost = intval($response_body['quotes'][0]['costSummary']['shipping']['amount']);

    }
    

    // var_dump( WC()->shipping->calculate_shipping( get_shipping_packages()));
    //var_dump($response_body);
    // echo $shippingCost;
  
    return $shippingCost;

}

function save_custom_data( $cart_item_data ) {

    // var_dump($cart_item_data);
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'save_custom_data', 10 );




add_action( 'woocommerce_cart_calculate_fees', 'custom_fee_based_on_cart_total', 10, 1 );

function custom_fee_based_on_cart_total( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;

    // The conditional Calculation
    $fee = get_prodigi_quote();

    if ( $fee != 0 ) 
        $cart->add_fee( __( "Shipping Cost", "woocommerce" ), $fee, false );
}




function disable_shipping_calc_on_cart( $show_shipping ) {
    if( is_cart() ) {
        return false;
    }else {
        return false;
    }
    return $show_shipping;
}
add_filter( 'woocommerce_cart_ready_to_calc_shipping', 'disable_shipping_calc_on_cart', 99 );


add_action( 'woocommerce_cart_totals_before_order_total', 'add_row' , 99);
// add_action( 'woocommerce_proceed_to_checkout', 'add_row' , 99);
function add_row() {
    ?>
        <div>
            <p class="shipping-calculator-button" style="font-size:20px; display:block; margin:0; padding-bottom:5px;">Select a shipping Method</p>
            <select name="prodigi_shipping" id="prodigi_shipping" style="width:100%; border-radius:2px; border: 1px solid #d4d4d4; padding:10px 20px;">
            <option value="budget">Budget</option>
            <option value="standard">Standard</option>
            <option value="express">Express</option>
        </select>

        </div>
       
</tr>

    <?php
}


