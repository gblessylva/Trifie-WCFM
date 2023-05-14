<?php

// "sku" => array('label' => __('SKU', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription pw-gift-card', 'label_class' => 'wcfm_title wcfm_half_ele_title'),
// do_action( 'wcfm_products_manage_variable_end', $product_id, $product_type );
function wcfm_product_manage_fields_variations_trifie($variation_fields,  $variations, $variation_shipping_option_array,  $variation_tax_classes_options ) {
	global $wp, $WCFM, $WCFMu ;
    $product_id = 0;
    if (isset($wp->query_vars['wcfm-products-manage']) && !empty($wp->query_vars['wcfm-products-manage'])) {
        $product_id = absint($wp->query_vars['wcfm-products-manage']);
    }


    $prodigi_product = $_GET['prodigi-id'];
    $has_admin_price = get_post_meta($prodigi_product, 'trifie_product_min_price', true);
    $saved_admin_price = get_post_meta($product_id, 'trifie_product_min_price', true);
    $currency = get_woocommerce_currency_symbol();
    if(isset( $prodigi_product)){
        $min_price_fields = array( 
            '_admin_min_price'=> array(
            'label' => __( 'Min Product Price (' . $currency. ')', 'wc-frontend-manager' ),
            'type'=>'number',
            'size'=>9,
            'value'=> $has_admin_price,
            "attributes" => array( "readonly" => true ),
            'id'=>'_admin_min_price',
            'class'=> 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription pw-gift-card',
            'label_class' => 'wcfm_title variable wcfm_half_ele_title'
            ),
        );
        
    }else{
        $trifie_sku = get_post_meta($product_id, '_printable_sku', true);
        $args = array(
            'post_type' => 'trifie_sku',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'prodigi_trifie_sku',
                    'value' =>$trifie_sku,
                    // 'compare' => '=',
                ),
            ), 
        );
        
        $the_query = get_posts($args);
        $pro_ID =$the_query[0]->ID;
        $set_min_price = get_post_meta($pro_ID, 'trifie_product_min_price', true);
    //    var_dump( $set_min_price);


    $min_price_fields = array( 
        '_admin_min_price'=> array(
        'label' => __( 'Min Product Price (' . $currency. ')', 'wc-frontend-manager' ),
        'type'=>'number',
        'size'=>9,
        'value'=> $set_min_price,
        "attributes" => array( "readonly" => true ),
        'id'=>'_admin_min_price',
        'class'=> 'wcfm-text wcfm_ele wcfm_half_ele variable variable-subscription pw-gift-card admin_min_price',
        'label_class' => 'wcfm_title variable wcfm_half_ele_title'
        
        )
    );
}

	$variation_fields = array_merge( $variation_fields, $min_price_fields);
	
	return $variation_fields;
}
add_filter( 'wcfm_product_manage_fields_variations', 'wcfm_product_manage_fields_variations_trifie', 5, 5 );


add_filter('wcfm_products_manage_variable_start', function($product_id){
    global $WCFM, $WCFMu;

    $options_array = array(""=>"Select Printable SKU");
    $args = array(
        'post_type' => 'trifie_sku',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $the_query = get_posts($args);
    // var_dump($the_query);

    $printable_sku = get_post_meta($product_id, '_printable_sku', true);
    // var_dump ($printable_sku);


    foreach($the_query as $post){

        $prodigi_sku = get_post_meta($post->ID, 'prodigi_trifie_sku', true);
        $options_array[$prodigi_sku] = $prodigi_sku;
        // var_dump($prodigi_sku);
        // $options_array[$post->ID] = $post->post_name;
    }

    $html = "";
    

    $html .= '<p class="variations_options wcfm_title"><strong>Select Printable SKU</strong></p>';
    $html .= '<select name="printable_sku" id="printable_sku" class="wcfm-select wcfm_ele variable variable-subscription pw-gift-card">';
    if($printable_sku ){
        $html .= '<option value="'.$printable_sku.'">'. strtoupper( $printable_sku).'</option>';
    }else{
        foreach($options_array as $key => $value){
            $html .= '<option value="'.$value.'">'. strtoupper( $value).'</option>';
        }
    }   
    
    
    $html .= '</select>';
    $button = '<button type="button" class=" wcfm_submit_button button button-primary update-sku-btn" id="update_printable_sku" style="margin-top:-5px">Update all SKUs</button>';

    echo $html;
    echo $button;
    

    

}, 1, 1);



// adding Data

function wcfm_product_data_variations_admin_price( $variations, $variation_id, $variation_id_key ) {
    global $WCFM, $WCFMu;
    
    if( $variation_id  ) {
        $variations[$variation_id_key]['_admin_min_price'] = get_post_meta( $variation_id, '_admin_min_price', true );
    }
    
    return $variations;
}
add_filter( 'wcfm_variation_edit_data', 'wcfm_product_data_variations_admin_price', 100, 3 );


// Save Data

function wcfm_product_variation_save_trifie( $wcfm_variation_data, $new_product_id, $variation_id, $variations, $wcfm_products_manage_form_data ) {
	global $WCFM, $WCFMu;
    // $printable_sku = $wcfm_products_manage_form_data['printable_sku'];
    // update_post_meta($new_product_id, '_printable_sku', $printable_sku);
	if( $variation_id  ) {

        function save_sku_fields($new_product_id, $wcfm_products_manage_form_data){
            $printable_sku = $wcfm_products_manage_form_data['printable_sku'];
            update_post_meta($new_product_id, '_printable_sku', $printable_sku);


        }

        add_action( 'after_wcfm_products_manage_meta_save', 'save_sku_fields', 10, 2 );

		update_post_meta( $variation_id, '_admin_min_price', $variations[ '_admin_min_price' ] );
       
	}
	
	return $wcfm_variation_data;
}

add_filter( 'wcfm_product_variation_data_factory', 'wcfm_product_variation_save_trifie', 100, 5 );


// function save_sku_fields($new_product_id, $wcfm_products_manage_form_data){
//     $printable_sku = $wcfm_products_manage_form_data['printable_sku'];
//     update_post_meta($new_product_id, '_printable_sku', $printable_sku);


// }

// add_action( 'after_wcfm_products_manage_meta_save', 'save_sku_fields', 10, 2 );

function make_variable_fields_required($variation_fields, $variations, $variation_shipping_option_array, $variation_tax_classes_options, $products_array){
  
    $user = wp_get_current_user();
    // $is_trife_admin = $user->roles['administrator'];        
    $variation_fields['regular_price']['custom_attributes'] = array( 'name' => 'pricing' );
    $variation_fields['regular_price']['label_class'] = 'wcfm_title wcfm_ele wcfm_half_ele_title variable pw-gift-card regular_variation_price'; 
    // var_dump($variation_fields['regular_price']['label_class']);
    if($user->roles!=['administrator']){ 
        if( isset( $variation_fields['regular_price'] ) ) {
            $variation_fields['regular_price']['custom_attributes'] = array( 'required' => 1 );
        }   
     }
    

    return $variation_fields;

}

add_filter( 'wcfm_product_manage_fields_variations', 'make_variable_fields_required', 50, 5 );

// function copy_produts_details($product_id ){
//     // $product = WC_Product($product_id);
//      $product = wc_get_product($prodigi_product);
//      if($product){
//          var_dump($product);
//     var_dump($product_id);
//      }
   
// }

// add_action( 'init', 'copy_produts_details' );
// $prodigi_product = $_GET['prodigi-id'];
// if($prodigi_product){
//     copy_produts_details($prodigi_product );
   
// }