<?php

function get_prodigi_sku($prodigi_sku){
    return $prodigi_sku;
}

add_action('woocommerce_before_checkout_form', 'sendOrder_details');

function sendOrder_details(){
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();

    foreach($items as $item){
    //    get the product ID
        $product_id = $item['product_id'];
        $prodigiSKU = get_post_meta($product_id, '_printable_sku', true);
       
        $prodigiSKU = get_prodigi_sku($prodigiSKU);
        echo $prodigiSKU;

    }

    
}
