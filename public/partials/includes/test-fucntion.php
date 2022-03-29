<?php

// Display a checkbox field after billing fields aggiunge spedizione assicurata al ceckout
add_action( 'woocommerce_after_checkout_billing_form', 'add_custom_checkout_checkbox', 20 );
function add_custom_checkout_checkbox(){

    woocommerce_form_field( 'custom_fee', array(
        'type'  => 'checkbox',
        'label' => __('Spedizione Assicurata 3€'),
        'class' => array( 'form-row-wide' ),
    ), '' );
}
// Ajax / jQuery script
add_action( 'wp_footer', 'custom_fee_script' );
function custom_fee_script() {
    // On checkoutpage
    if( ( is_checkout() && ! is_wc_endpoint_url() ) ) :
    ?>
    <script type="text/javascript">
    jQuery( function($){
        if (typeof woocommerce_params === 'undefined')
            return false;
        console.log('defined');
        $('input[name=custom_fee]').click( function(){
            var fee = $(this).prop('checked') === true ? '1' : '';
            $.ajax({
                type: 'POST',
                url: woocommerce_params.ajax_url,
                data: {
                    'action': 'custom_fee',
                    'custom_fee': fee,
                },
                success: function (result) {
                    $('body').trigger('update_checkout');
                    console.log(result);
                },
            });
        });
    });
    </script>
    <?php
    endif;
}
// Get the ajax request and set value to WC session
add_action( 'wp_ajax_custom_fee', 'get_ajax_custom_fee' );
add_action( 'wp_ajax_nopriv_custom_fee', 'get_ajax_custom_fee' );
function get_ajax_custom_fee() {
    if ( isset($_POST['custom_fee']) ) {
        WC()->session->set('custom_fee', ($_POST['custom_fee'] ? '1' : '0') );
        echo WC()->session->get('custom_fee');
    }
    die();
}
// Add / Remove a custom fee / ULTIMO PER SPEDIZIONE ASSICURATA/
add_action( 'woocommerce_cart_calculate_fees', 'add_remove_custom_fee', 10, 1 );
function add_remove_custom_fee( $cart ) {
    // Only on checkout
    if ( ( is_admin() && ! defined( 'DOING_AJAX' ) ) || is_cart() )
        return;
    $fee_amount = 3;
    if( WC()->session->get('custom_fee') )
        $cart->add_fee( __( 'Spedizione Assicurata', 'woocommerce'), $fee_amount );
}