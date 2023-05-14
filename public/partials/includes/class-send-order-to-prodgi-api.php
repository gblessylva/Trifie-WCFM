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
        // echo $prodigiSKU;

    }

    
}


// function copy_product($source_product_id, $target_product_name) {

//     // Get the source product
//     $source_product = wc_get_product($source_product_id);
  
//     // Create the target product
//     $target_product = new WC_Product();
//     $target_product->set_name($target_product_name);
//     $target_product->set_status($source_product->get_status());
//     $target_product->set_type($source_product->get_type());
//     $target_product->set_parent_id($source_product->get_parent_id());
//     $target_product->set_sku($source_product->get_sku());
//     $target_product->set_price($source_product->get_price());
//     $target_product->set_regular_price($source_product->get_regular_price());
//     $target_product->set_sale_price($source_product->get_sale_price());
//     $target_product->set_stock_quantity($source_product->get_stock_quantity());
//     $target_product->set_manage_stock($source_product->get_manage_stock());
//     $target_product->set_visibility($source_product->get_visibility());
//     $target_product->set_description($source_product->get_description());
//     $target_product->set_short_description($source_product->get_short_description());
//     $target_product->set_permalink($source_product->get_permalink());
//     $target_product->set_weight($source_product->get_weight());
//     $target_product->set_dimensions($source_product->get_dimensions());
//     $target_product->set_tax_class_id($source_product->get_tax_class_id());
//     $target_product->set_downloadable($source_product->get_downloadable());
//     $target_product->set_virtual($source_product->get_virtual());
//     $target_product->set_shipped_individually($source_product->get_shipped_individually());
//     $target_product->set_allow_backorders($source_product->get_allow_backorders());
//     $target_product->set_backorder_qty_limit($source_product->get_backorder_qty_limit());
//     $target_product->set_is_in_stock($source_product->get_is_in_stock());
//     $target_product->set_featured($source_product->get_featured());
//     $target_product->set_purchase_note($source_product->get_purchase_note());
  
//     // Copy the product attributes
//     foreach ($source_product->get_attributes() as $attribute) {
//       $target_product->set_attribute($attribute->get_attribute_name(), $attribute->get_value());
//     }
  
//     // Copy the product meta
//     foreach ($source_product->get_meta_data() as $meta_key => $meta_value) {
//       $target_product->add_meta_data($meta_key, $meta_value);
//     }
  
//     // Save the target product
//     $target_product->save();
  
//     // Return the target product ID
//     return $target_product->get_id();
//   }
  
  
  function copy_product($product_id) {
    // Get the product object.
    $product = wc_get_product($product_id);
  
    // Create a new product object.
    $new_product = new WC_Product();
  
    // Copy the product data.
    $new_product->set_name($product->get_name());
    $new_product->set_description($product->get_description());
    $new_product->set_short_description($product->get_short_description());
    $new_product->set_sku($product->get_sku());
    $new_product->set_price($product->get_price());
    $new_product->set_regular_price($product->get_regular_price());
    $new_product->set_sale_price($product->get_sale_price());
    $new_product->set_stock_quantity($product->get_stock_quantity());
    $new_product->set_manage_stock($product->get_manage_stock());
    $new_product->set_status($product->get_status());
    $new_product->set_tax_class($product->get_tax_class());
    $new_product->set_permalink($product->get_permalink());
    $new_product->set_attributes($product->get_attributes());
    $new_product->set_product_type($product->get_product_type());
    $new_product->set_meta_data($product->get_meta_data());
  
    // Save the new product.
    $new_product->save();
  
    // Return the new product ID.
    return $new_product->get_id();
  }
  
  add_action('woocommerce_before_single_product', function() {
    // Get the product ID.
    $product_id = get_the_ID();
  
    // Add a button to the product page.
    echo '<button id="copy-product-button">Copy Product</button>';
  });
  
  add_action('wp_ajax_copy_product', function() {
    // Check if the request is coming from an authenticated user.
    if (!is_user_logged_in()) {
      wp_send_json_error();
      exit;
    }
  
    // Get the product ID from the request.
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
  
    // Copy the product.
    $new_product_id = copy_product($product_id);
  
    // If the product was copied successfully, send a success response.
    if ($new_product_id) {
      wp_send_json_success([
        'message' => 'Product copied successfully.',
        'product_id' => $new_product_id,
      ]);
    } else {
      wp_send_json_error();
    }
  });
  