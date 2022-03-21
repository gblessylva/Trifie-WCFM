<?php

 

add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
{

    $fields['prodigi_shipping'] = array(
        'label' => __('Shipping Method', 'woocommerce'), // Add custom field label
        'required' => true, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'select', // add field type
        'options' => array(
            'budget' => __('Budget', 'woocommerce'),
            'standard' => __('Standard', 'woocommerce'),
            'express' => __('Express', 'woocommerce'),
        ),
        'class' => array('custom-shipping'),    // add class name
        'default' => 'budget', // set default value
        
    );

    return $fields;
}


add_filter( 'woocommerce_checkout_fields', 'misha_email_first' );

function misha_email_first( $checkout_fields ) {
	$checkout_fields['billing']['prodigi_shipping']['priority'] = 4;
	return $checkout_fields;
}


add_action('wp_ajax_get_prodigi_quote', 'get_prodigi_quote');
add_action('wp_ajax_nopriv_prodigi_quote', 'get_prodigi_quote');



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
    
  
    return $shippingCost;

}

function save_custom_data( $cart_item_data ) {

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


