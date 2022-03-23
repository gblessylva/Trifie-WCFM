<?php


add_filter( 'product_type_selector', 'trife_add_printable_product_type' );
 
function trife_add_printable_product_type( $types ){
    $types[ 'printable' ] = 'Printable product';
    return $types;
}
 
/* 
 * Add New Product Type Class
 */

add_action( 'init', 'trife_create_printable_product_type' );
 
function trife_create_printable_product_type(){
    class WC_Product_Printable extends WC_Product {
        public function get_type() {
            return 'printable';
        }
    }
}
 
/* 
 * Load New Product Type Class
 */

add_filter( 'woocommerce_product_class', 'trife_woocommerce_product_class', 10, 2 );
 
function trife_woocommerce_product_class( $classname, $product_type ) {
    if ( $product_type == 'printable' ) { 
        $classname = 'WC_Product_Printable';
    }
    return $classname;
}
// Adding Price fields & inventory to printable product type
add_action('admin_footer', 'trife_printable_product_admin_printable_js');
function trife_printable_product_admin_printable_js() {

    if ('product' != get_post_type()) :
        return;
    endif;
    ?>
    <script type='text/javascript'>
        jQuery(document).ready(function () {
            //for Price tab
            // jQuery('.options_group.pricing').addClass('show_if_printable').show();
            
            //for Inventory tab
            // jQuery('.inventory_options').addClass('show_if_printable').show();
            // jQuery('#inventory_product_data ._manage_stock_field').addClass('show_if_printable').show();
            // jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_printable').show();
            // jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_printable').show();
        });
    </script>
    <?php
}
// Get Trifie Template Details



// add the settings under ‘General’ sub-menu
add_action( 'woocommerce_product_options_general_product_data', 'trife_add_printable_settings' );
function trife_add_printable_settings() {
    global $woocommerce, $post;
    echo '<div class="options_group">';

       woocommerce_wp_text_input(
        array(
         'id'                => '_regular_price',
         'label'             => __( 'Price', 'woocommerce' ),
         'placeholder'       => '',
         'desc_tip'    => 'true',
         'description'       => __( 'Please enter a price higher than the normal price', 'woocommerce' ),
         'type'              => 'text',
         'wrapper_class' => 'show_if_printable',
         ));
         
         woocommerce_wp_select( array( 'id' => '_printable_sku', 'wrapper_class' => 'custom-stock-status', 
         'label' => __( 'Printable SKU', 'woocommerce' ), 
         'options' => array('CLASSIC-POST-GLOS-6X4', 'AU3-TEE-U-G-64000', 'AU3-TEE-U-B-3501', 'GLOBAL-FRA-CAN-12X24', 'GLOBAL-FRA-CAN-12X12', 'GLOBAL-FRA-CAN-32X32', 'GLOBAL-FRA-CAN-24X36', 'GLOBAL-FRA-CAN-30X40',
         'GLOBAL-FRA-CAN-18X24','GLOBAL-FRA-CAN-10X12', 'GLOBAL-FRA-CAN-12X18', 'GLOBAL-FRA-CAN-20X28', 'GLOBAL-FRA-CAN-28X28','GLOBAL-FRA-CAN-20X20', 'GLOBAL-FRA-CAN-40X40', 'GLOBAL-FRA-CAN-20X24', 'GLOBAL-FRA-CAN-24X24', 'GLOBAL-FRA-CAN-8X8', 'GLOBAL-FRA-CAN-10X10', 'GLOBAL-FRA-CAN-12X16',
        ), 'desc_tip' => true, 'description' => __( 'Select the Printable Product SKU', 'woocommerce' ) ) );


        global $post;

    // Create a checkbox for product purchase status
      woocommerce_wp_checkbox(
       array(
       'id'            => '_is_purchasable',
       'label'         => __('Is Purchasable', 'woocommerce' ),
       'wrapper_class' => 'show_if_printable',
       ));

    echo '</div>';
}
add_action( 'woocommerce_process_product_meta', 'trife_save_printable_settings' );
function trife_save_printable_settings( $post_id ){
    $price_field = $_POST['_regular_price'];
    $price_with_tax = $price_field + 20;
    update_post_meta( $post_id, '_regular_price', esc_attr( $price_with_tax) );

    // save purchasable option
    $trife_purchasable = isset( $_POST['_is_purchasable'] ) ? 'yes' : 'no';
    update_post_meta( $post_id, '_is_purchasable', $trife_purchasable );
    $woocommerce_select = $_POST['_printable_sku'];
    if( !empty( $woocommerce_select ) )
        update_post_meta( $post_id, '_printable_sku', esc_attr( $woocommerce_select ) );
    else
    update_post_meta( $post_id, '_printable_sku', 'DEFAULT' );
    

}

// add_filter( 'wcfm_product_fields_stock', 'set_sku_readonly', 50, 3 );
add_filter( 'wcfm_product_fields_stock', function( $stock_fields, $product_id, $product_type ) {
		if( isset( $stock_fields['sku'] ) ) {
            $prodigi_product = $_GET['prodigi-id'];
            $new_trifie_product   = get_post( $prodigi_product );
            $store_user = wcfmmp_get_store();
            $author = $store_user->data->user_nicename;
            $total_vendor_products = count_user_posts( $store_user->data->ID, 'product' ) + 1;
			$stock_fields['sku']['attributes']['readonly'] = true;
            $trife_product_id = get_post_meta($prodigi_product, 'trifie_product_id', true);
            $generated_sku = $author .'-' .$trife_product_id. '-' . $total_vendor_products;
            if (isset($prodigi_product)) {
                $stock_fields['sku']['value']=$generated_sku;
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
                $trife_product_id = get_post_meta($pro_ID, 'trifie_product_id', true);
                $generated_sku = $author .'-' .$trife_product_id. '-' . $total_vendor_products;
                $stock_fields['sku']['value']=$generated_sku;
            }
           
		}
  return $stock_fields;
}, 50, 3 );




// Create Field if Template is not copied new fields
function add_normal_templated_field( $fields, $product_id, $product_type, $wcfm_is_translated_product, $wcfm_wpml_edit_disable_element ) {
    $wcfm_capability_options = get_option( 'wcfm_capability_options', array() );
    $is_new_val_cap = ( isset( $wcfm_capability_options['_printable_sku'] ) ) ? $wcfm_capability_options['_printable_sku'] : 'no';
    if($is_new_val_cap=='yes') return $fields;
    
    $printable_post_meta =  get_post_meta( $product_id, '_printable_sku', true ) ;
    // var_dump($printable_post_meta  );
    // WP_Query arguments
    $args = array(
        'post_type'              => array( 'trifie_sku' ),
        'posts_per_page' => '-1',
        'order' => 'ASC',
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'prodigi_trifie_sku',
    ); 
    $query = new WP_Query( $args );
    $pritable_sku_array = array("" => "Select SKU");
    if($query->have_posts()){
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_title = get_the_title();
            $trifie_post_id = get_the_ID();
            $options = get_post_meta($trifie_post_id, 'prodigi_trifie_sku', true);
            $options = array_map('trim', explode(',', $options));
            foreach($options as $option){
                $pritable_sku_array[$option] = $option;
            }
            

        }
       
        wp_reset_postdata();
    }   
    
    foreach($pritable_sku_array as $option){
        if($printable_post_meta !=''){
            $new_field = array( "_printable_sku" => 
            array(  
            'type' => 'select', 
            'label'=> 'Printable Product SKU',
            'class' => 'wcfm-select wcfm-printable wcfm_ele wcfm_product_type  simple non-booking non-variable-subscription non-job_package non-resume_package non-redq_rental non-accommodation-booking' . ' ' . $wcfm_wpml_edit_disable_element,
            'desc_class' => 'wcfm_title wcfm_ele virtual_ele_title checkbox_title simple non-booking non-variable-subscription non-job_package non-resume_package non-redq_rental non-accommodation-booking' . ' ' . $wcfm_wpml_edit_disable_element, 
            'value' =>  $printable_post_meta,
            'options'  => array($printable_post_meta=>$printable_post_meta),
            // 'selected' => $is_new_val,
            'id'=>'_printable_sku'
    
            )
         );

        }else{
            $new_field = array( "_printable_sku" => 
            array( 
            'type' => 'select', 
            'label'=> 'Printable Product SKU',
            'class' => 'wcfm-select wcfm-printable wcfm_ele wcfm_product_type  simple non-booking non-variable-subscription non-job_package non-resume_package non-redq_rental non-accommodation-booking' . ' ' . $wcfm_wpml_edit_disable_element,
            'desc_class' => 'wcfm_title wcfm_ele virtual_ele_title checkbox_title simple non-booking non-variable-subscription non-job_package non-resume_package non-redq_rental non-accommodation-booking' . ' ' . $wcfm_wpml_edit_disable_element, 
            'value' =>  $printable_post_meta,
            'options'  => $pritable_sku_array,
            'selected' => $printable_post_meta,
            'id'=>'_printable_sku_field'
    
            )
         );
            
        }


        if($product_type != 'variable'){
       
         return array_slice( $fields, 0, 4, true ) + $new_field + array_slice( $fields, 4, count( $fields ) - 4, true );
        }
            
    }

    return $fields;
}

function load_sku_script($endpoint)
{
    global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
    wp_enqueue_script( 'update_printable_sku', $plugin_url .'../js/update-sku.js', array( 'jquery' ), $WCFM->version, true );
    wp_localize_script( 'update_printable_sku', 'SkuAjax', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                  ));
}

add_action('wp_enqueue_scripts', 'load_sku_script');


add_action('wp_ajax_update_printable_sku', 'update_printable_sku');
add_action('wp_ajax_no_priv_update_printable_sku','update_printable_sku');

function update_printable_sku(){
    global $current_user;  
    $user_args = array(
        'author'        =>  $current_user->ID, 
        'post_type'     =>  'product',
        'status'       =>  array('publish','draft', 'pending', 'private', 'protected', 'future', ),
        'posts_per_page' => -1 // no limit
      );

      $current_user_posts = get_posts( $user_args );
      $total = count($current_user_posts) + 1;

    $post_name = $_POST['data_id'];
    $sku = $post_name;
    $trifie_product = get_posts($args = array('post_type' => 'trifie_sku',  'meta_value' => $post_name));
    $trifie_post_id = $trifie_product[0]->ID;
    $store_user = wcfmmp_get_store();
    $author = $store_user->data->user_nicename;
                      

    $total_vendor_products = count_user_posts( $store_user->data->ID, 'product' ) + 1;
    $response = [];

     $row = array(
        // 'status' => 'success',
        'message' => 'SKU Updated',
        'author' => $author,
        'total_vendor_products' => $total,
        'product' => array(
            'trifie_post_id' => $trifie_post_id,
            'single_prodigi_id' => get_post_meta($trifie_post_id, 'trifie_product_id', true),
            'single_prodigi_cost' => get_post_meta($trifie_post_id, 'trifie_product_cost', true),
            'single_prodigi_min_cost' => get_post_meta($trifie_post_id, 'trifie_product_min_price', true),
        ),
        
     );

    $response['data'] = $row;

    wp_send_json($response);

    die();
}


// Create Field if Template is not copied new fields
function add_copied_templated_field( $fields, $product_id, $product_type, $wcfm_is_translated_product, $wcfm_wpml_edit_disable_element ) {
    $wcfm_capability_options = get_option( 'wcfm_capability_options', array() );
    $is_new_val_cap = ( isset( $wcfm_capability_options['_printable_sku'] ) ) ? $wcfm_capability_options['_printable_sku'] : 'no';
    if($is_new_val_cap=='yes') return $fields;
    $is_new_val = ( get_post_meta( $product_id, '_printable_sku', true ) );
    $prodigi_product = $_GET['prodigi-id'];

    $trifie_args = array(
    'post_type'              => array( 'trifie_sku' ),
    'ID'=> $prodigi_product
    );
    $query = new WP_Query($trifie_args);
    $prodigi_sku = get_post_meta($prodigi_product, 'prodigi_trifie_sku', true);
    // var_dump($prodigi_sku);

    $new_field = array( "_printable_sku" => 
    array( 'desc' => __( 'Printable Product SKU', 'wc-frontend-manager' ), 
    'type' => 'select', 
    'label'=> 'Printable Product SKU',
    'class' => 'wcfm-select wcfm-printable wcfm_ele wcfm_product_type  simple variable external grouped booking' . ' ' . $wcfm_wpml_edit_disable_element,
    'desc_class' => 'wcfm_title wcfm_ele virtual_ele_title checkbox_title simple non-booking non-variable-subscription non-job_package non-resume_package non-redq_rental non-accommodation-booking' . ' ' . $wcfm_wpml_edit_disable_element, 
    'value' =>  $prodigi_sku,
    'options'  => array( $prodigi_sku => $prodigi_sku),
    'selected' => $prodigi_sku,
    "attributes" => array( "readonly" => true ),

    )
    );
   
    return array_slice( $fields, 0, 5, true ) + $new_field + array_slice( $fields, 5, count( $fields ) - 5, true );
    return $fields;
}

// Add Upload function
function add_file_upload_fields($fields, $product_id, $product_type, $wcfm_is_translated_product, $wcfm_wpml_edit_disable_element){
    $printable_image_src = get_post_meta( $product_id, '_printable_image', true );
    $new_field = array(
        '_printable_image'=> array(
        'type'=>'hidden',
        'size'=>9,
        'value'=> $printable_image_src,
        "attributes" => array( "readonly" => true ),
        'id'=>'_printable_image',
        'class'=> 'wcfm-hidden _printable_sku wcfm-printable wcfm_ele wcfm_product_type  simple variable external grouped booking',
        
        ),
    );
   
    return array_slice( $fields, 0, 5, true ) + $new_field + array_slice( $fields, 5, count( $fields ) - 5, true );
};

// Display Min Price
function add_min_price_fields($fields, $product_id, $product_type, $wcfm_is_translated_product, $wcfm_wpml_edit_disable_element){
    $prodigi_product = $_GET['prodigi-id'];
    // var_dump($product_type);

    $has_admin_price = get_post_meta($prodigi_product, 'trifie_product_min_price', true);
    $saved_admin_price = get_post_meta($product_id, 'trifie_product_min_price', true);
    $currency = get_woocommerce_currency_symbol();
    if(isset( $prodigi_product)){
        $new_field = array( 
            '_admin_min_price'=> array(
            'label' => __( 'Min Product Price (' . $currency. ')', 'wc-frontend-manager' ),
            'type'=>'number',
            'size'=>9,
            'value'=> $has_admin_price,
            "attributes" => array( "readonly" => true ),
            'id'=>'_admin_min_price',
            'class'=> 'wcfm-text wcfm_ele wcfm_non_negative_input wcfm_half_ele simple simple external grouped booking',
            
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


    $new_field = array( 
        '_admin_min_price'=> array(
        'label' => __( 'Min Product Price (' . $currency. ')', 'wc-frontend-manager' ),
        'type'=>'number',
        'size'=>9,
        'value'=> $set_min_price,
        "attributes" => array( "readonly" => true ),
        'id'=>'_admin_min_price',
        'class'=> 'wcfm-text wcfm_ele wcfm_non_negative_input wcfm_half_ele simple  external grouped booking',
        
        ),
    );
}
    if($product_type != 'variable'){
        return array_slice( $fields, 0, 5, true ) + $new_field + array_slice( $fields, 5, count( $fields ) - 5, true );
    }
    
};


if(isset($_GET['prodigi-id'])){
    add_filter('wcfm_product_manage_fields_general', 'add_copied_templated_field', 9, 6);

}else{
    add_filter( 'wcfm_product_manage_fields_general', 'add_normal_templated_field', 9, 6 );
}

add_filter( 'wcfm_product_manage_fields_general', 'add_file_upload_fields', 9, 6 );

add_filter('wcfm_product_manage_fields_pricing', 'add_min_price_fields', 90, 6);

// // Saving product fields
function save_new_field_values( $trifie_product_id, $wcfm_products_manage_form_data ) {
    $user = wp_get_current_user();
    $is_trife_admin = $user->roles['administrator'];        
    // var_dump($is_admin_trife_admin);
    $is_printable_sku_value = $wcfm_products_manage_form_data['_printable_sku'] ;
    $has_admin_price = $wcfm_products_manage_form_data['_admin_min_price'] ;

    $pritable_image_url = $wcfm_products_manage_form_data['_printable_image'] ;
    $admin_price = $wcfm_products_manage_form_data['regular_price'];
    update_post_meta( $trifie_product_id, '_printable_sku', $is_printable_sku_value );
    update_post_meta( $trifie_product_id, '_printable_image', $pritable_image_url );
    update_post_meta($trifie_product_id, 'trifie_product_min_price', $has_dmin_price);

    if($user->roles==['administrator']){
        update_post_meta($trifie_product_id, '_is_admin_product', 'yes');
        update_post_meta($trifie_product_id, 'trifie_product_min_price', $has_admin_price);
        // var_dump('He is admin');
       
    }else{
        update_post_meta($trifie_product_id, '_is_admin_product', 'no');
    }
}

function wcfm_required_product_fields_pricing( $price_fields, $product_id, $product_type ) {
	$is_product_template = $POST['template_value'];
    $user = wp_get_current_user();
    if($user->roles !=['administrator']){
        if( isset( $price_fields['regular_price'] ) )  { $price_fields['regular_price']['custom_attributes'] = array( 'required' => 1 ); }

    }
 
    return $price_fields;
}
add_filter( 'wcfm_product_manage_fields_pricing', 'wcfm_required_product_fields_pricing', 50, 3 );

function add_high_resultion_image ($product_id){
        $printable_image_src = get_post_meta( $product_id, '_printable_image', true );
        // var_dump($printable_image_src);
        if($printable_image_src){
            $src = $printable_image_src;
        }else{
            $src = '';
            // plugin_dir_url(__FILE__) . '../../includes/libs/uploads/images/Placeholder.png';
        }

    echo "
        <div class='high-res-image'>
        <h4> Select Full Resolution Image </h3>
        <img src='$src' id='high-res'>
        <button id='printable-image-selector' class='wcfm-button'> Select Image </button> 
            </div>
        ";
        
}


add_action('wcfm_product_manager_right_panel_before', 'add_high_resultion_image', 10, 2);

function set_as_product_template ($product_id){
    global $wp, $WCFM, $wc_product_attributes;

    $user = wp_get_current_user();
    if($user->roles==['administrator']){
            $is_product_template = ( get_post_meta( $product_id, '_is_product_tamplate', true) == 'enable' ) ? 'enable' : '';
            $WCFM->wcfm_fields->wcfm_generate_form_field(array(
                "is_product_template" => array('desc' => __('Set as Product Template', 'wc-frontend-manager') , 
                'type' => 'checkbox', 
                'class' => 'wcfm-checkbox wcfm_ele wcfm_half_ele_checkbox simple booking variable variable-subscription', 
                'desc_class' => 'padded-paragraph wcfm_title wcfm_ele virtual_ele_title checkbox_title simple booking variable variable-subscription',
                'value' => $is_product_template, 
                'dfvalue'=>$is_product_template,
            ),
            "template_value" => array( 
                'type' => 'hidden', 
                'class' => 'wcfm-text wcfm_ele wcfm_half_ele_checkbox simple booking variable variable-subscription', 
                'value' => '', 
            )
            )
            ) ;
                  
            } 

              
}

function save_as_product_template($trifie_product_id, $wcfm_products_manage_form_data){
    $is_product_template = $wcfm_products_manage_form_data['is_product_template'];
    if($is_product_template){
        update_post_meta($trifie_product_id, '_is_product_tamplate', 'enable');
        wp_update_post(array(
            'ID'    =>  $trifie_product_id,
            'post_status'   =>  'Template'
            ));
    }else{
        delete_post_meta($trifie_product_id, '_is_product_tamplate', 'enable');
    }
}

add_action('wcfm_product_manager_left_panel_before', 'set_as_product_template', 10, 2);
// add_filter('wcfm_products_manage_variable_start', 'set_as_product_template',1, 1);

add_action( 'after_wcfm_products_manage_meta_save', 'save_as_product_template', 10, 2 );


add_action( 'after_wcfm_products_manage_meta_save', 'save_new_field_values', 10, 2 );

// Add Password Function

add_filter('after_wcfm_products_manage_taxonomies', 'wcfm_add_product_password');

function wcfm_add_product_password($product_id){
        $the_post = get_post($product_id );
        $status = get_post_status($product_id);
        $pasworded = get_post_meta($product_id, '_post_dwp', true);

    ?>

<div class="misc-pub-section misc-pub-visibility" id="visibility wcfm-visibility" style = "padding: 15px!important; margin-left: 10px !important;
    border: 1px solid #d1d1d1d1 !important;
    border-radius: 5px; !important" >
			Visibility:			<span id="wcfm-post-visibility-display">	Public</span>

			<a href="#visibility" class="wcfm-product-edit-visibility wcfm-hide-if-no-js" role="button"><span aria-hidden="true">Edit</span> 
            <span class="screen-reader-text">Edit visibility</span></a>

				<div id="wcfm-post-visibility-select" class="wcfm-hide-if-js" style="display: block; margin-top: 10px;">
					<input type="hidden" name="hidden_post_password" id="hidden-post-password" value="">
					
					<input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility"  value="public" >
					<input type="radio" name="visibility" class='visibility-radio-public' id="visibility-radio-public visbility-radio" value="publish"
                        <?php if($status != 'private' || $password == '' ){
                            echo 'checked';
                        }?>
                        
                    > <label for="visibility-radio-public" class="selectit" style="padding-bottom: 10px;">Public</label><br>
					<input type="radio" name="visibility" class='password-check' id="wcfm-visibility-radio-password visbility-radio" 
                    <?php 
                    
                    
                    if( $pasworded !="" || $status == 'published'){
                                  echo 'checked';
                             } ?>
                    value="password"> 
                    <label for="visibility-radio-password" class="selectit" style="padding-bottom: 10px;">Password protected</label><br>
					<span id="wcfm-password-span" >

                        <label for="post_password">Password:</label> 
                        <?php

                                
                        ?>
                        <input type="text" style="margin-bottom: 10px;" name="post_password" id="wfcm_post_password" placeholder= "Choose a product password" 
                            value='<?php 
                            echo esc_attr( $pasworded );
                             ?>' 
                             
                             maxlength="255"><br>
                            
                    </span>
					<input type="radio" name="visibility" id="visibility-radio-private visbility-radio" 
                    <?php 
                        if($status == 'private'){
                            echo 'checked';
                        }?>
                    value="private"> <label for="visibility-radio-private" style="padding-bottom: 10px;" class="selectit">Private</label><br>
                    
                    <input type="radio" name="visibility" class='visibility-radio-template' id="visibility-radio-template visbility-radio" 
                    <?php 
                        if($status == 'template'){
                            echo 'checked';
                        }?>
                    value="template"> <label for="visibility-radio-template" style="padding-bottom: 10px;" class="selectit">Template</label><br>
					<p>
						<a href="#visibility" class="wcfm-save-post-visibility wcfm-hide-if-no-js button">OK</a>
						<a href="#visibility" class="wcfm-cancel-post-visibility wcfm-hide-if-no-js button-cancel">Cancel</a>
					</p>
				</div>
					</div>
    <?php
   
}

// // Saving product fields
function save_password_field_values( $trifie_product_id, $wcfm_products_manage_form_data ) {
    $is_visibility = $wcfm_products_manage_form_data['visibility'];
    $has_product_password = $wcfm_products_manage_form_data['post_password'];
    $is_product_template = $wcfm_products_manage_form_data['is_product_template'];
    if($is_visibility == 'password'){
        $pass_arg = array(
            'ID'            => $trifie_product_id,
            'post_status'   => 'publish',
            'post_password' => $has_product_password,
            'post_name'     => sanitize_title( $wcfm_products_manage_form_data['post_title'] ),
        );

        add_action( 'the_post', function( $post ){
            if ( $post->post_type != 'product' ) {
                return;
            }	
            $post->post_password = $has_product_password;
        } );
        wp_update_post($pass_arg);
        update_post_meta($trifie_product_id, '_post_dwp', $has_product_password);
        
        $terms = array( 'exclude-from-search', 'exclude-from-catalog' ); // for hidden..
        wp_set_post_terms( $trifie_product_id, $terms, 'product_visibility', false ); 
        
    }else if($is_visibility == 'template'){
        wp_update_post(array(
            'ID'            => $trifie_product_id,
            'post_status'=> 'template'
        ));
        update_post_meta($trifie_product_id, '_is_product_tamplate', 'enable');
    }else if($is_visibility != 'password'){
        $arg = array(
            'ID'            => $trifie_product_id,
            // 'post_status'   => $is_visibility,
            'post_password' => '',
            'post_name'     => sanitize_title( $wcfm_products_manage_form_data['post_title'] ),
        );
        wp_update_post($arg);
        delete_post_meta($trifie_product_id, '_post_dwp', '');
        delete_post_meta($trifie_product_id, '_is_product_tamplate', '');
        $terms = array( 'exclude-from-search', 'exclude-from-catalog' ); // for hidden..
        wp_set_post_terms( $trifie_product_id, $terms, 'product_visibility', false );
        wp_remove_object_terms($trifie_product_id, $terms, 'product_visibility');
    }

   

}
// Register Protected Post status
function wpdocs_custom_post_status(){
    register_post_status( 'protected', array(
        'label'                     => _x( 'Protected', 'product' ),
        'public'                    => true,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Protected <span class="count">(%s)</span>', 'Protected <span class="count">(%s)</span>' ),
    ) );
}

add_action( 'init', 'wpdocs_custom_post_status' );

// Register Template Post Stattus
// Register Custom Status
function register_template_status() {

	$args = array(
		'label'                     => _x( 'Template', 'Status General Name', 'trifie' ),
		'label_count'               => _n_noop( 'Template (%s)',  'Templates (%s)', 'trifie' ), 
		'public'                    => false,
		'show_in_admin_all_list'    => false,
		'show_in_admin_status_list' => false,
		'exclude_from_search'       => true,
	);
	register_post_status( 'Template', $args );

}
add_action( 'init', 'register_template_status', 0 );

add_action( 'after_wcfm_products_manage_meta_save', 'save_password_field_values', 10, 2 );


wp_enqueue_script( 'admin-page-script', plugin_dir_url( __FILE__ ) . '../js/trife-control.js', array('jquery'), '1.0', true );

wp_enqueue_script('select2-script', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',  array('jquery'), '4.1.0', true);
wp_enqueue_style('select2-styles', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',);
wp_enqueue_style('manager-styles',plugin_dir_url(__FILE__) . '../js/trife-manager.css');

