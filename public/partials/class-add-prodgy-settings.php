<?php

apply_filters( 'wcfm_is_allow_sub_category_attributes_mapping', true );

add_action( 'end_wcfm_settings', 'wcfmt_prodigy_settings', 1 );
add_action( 'wcfm_settings_update', 'wcfmt_prodigy_settings_update', 20 );
// add_action( 'wp_enqueue_scripts', 'load_select2_scripts' );


//  function load_select2_scripts() {
//     wp_register_style( 'select2css', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.css', false, '1.0', 'all' );
//     wp_register_script( 'select2', '//cdnjs.cloudflare.com/ajax/libs/select2/3.4.8/select2.js', array( 'jquery' ), '1.0', true );
//     wp_enqueue_style( 'select2css' );
//     wp_enqueue_script( 'select2' );
// }




function wcfmt_prodigy_settings($wcfm_options){
   global $WCFM;
   ?>
       <!-- Collapsable- set icon and menu name attached to wcfm settings page -->
       <div class="page_collapsible" id="wcfm_settings_form_delivery_head">
           <label class="wcfmfa fa-dolly"></label>
           <?php _e('Prodigy Settings', 'wc-frontend-manager-prodigy'); ?><span></span>
       </div>

       <div class="wcfm-container">

       <div id="wcfm_settings_form_delivery_expander" class="wcfm-content">
            <h2><?php _e('Prodigy API Settings', 'wc-frontend-manager-prodigy'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <?php  do_action( 'wcfmd_prodigy_settings_before' ); ?>
            <div class="wcfm_clearfix"></div>
            <div id='message'>
               <h2><?php _e('Please Read Instructions carefully', 'wc-frontend-manager-prodigy'); ?></h2>
               <p> <a href="https://sandbox-beta-dashboard.pwinty.com/">Sandbox</a>  is Prodigy's testing environment. It will not fulfil your orders, and you will not be charged for using it.</p>  
               <p> <a href="https://dashboard.prodigi.com/">Live</a>  is Prodigy's production environment. Any orders that are placed here will be produced and shipped <span class='warning'>ONLY ENABLE IF YOU WANT TO GO LIVE</span> </p>
               <p>The default API endpoints have been enter, ONLY modify if the url changes in the future</p>
               <p>Your API Keys are important. Ensure you coppied the right ones</p>   
           </div>
           <?php
               $enable_live = wcfm_get_option('wcfm_prodigy_live_mode', '');
               $test_key = wcfm_get_option('wcfm_prodigy_test_api_key', 'test_f47cb388-11bb-4453-b337-62574d0eae54');
               $test_url = wcfm_get_option('wcfm_prodigy_test_api_url', 'https://api.sandbox.prodigi.com/v4.0/');
               $live_key = wcfm_get_option('wcfm_prodigy_live_api_key', '');
               $live_url = wcfm_get_option('wcfm_prodigy_live_api_url', 'https://api.prodigi.com/v4.0/');
               $live_order_url = wcfm_get_option('wcfm_prodigy_live_order_api_url', 'https://api.prodigi.com/v4.0/orders?Top=10');
               $test_order_url = wcfm_get_option('wcfm_prodigy_test_order_api_url', 'https://api.sandbox.prodigi.com/v4.0/orders?Top=10');

               $WCFM->wcfm_fields->wcfm_generate_form_field( array(
                   "wcfm_prodigy_live_mode" => array('label' => __('Enable Live Mode', 'wc-frontend-manager'), 
                                               'type' => 'checkbox',
                                               'class' => 'wcfm-checkbox  wcfm_ele', 
                                               'value' => 'yes',
                                               'dfvalue'=> $enable_live,
                                               'label_class' => 'wcfm_title wcfm_ele',
                                               ),
                   "wcfm_prodigy_test_api_key" => array('label' => __('TEST API KEY', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $test_key
                                                ),
                   "wcfm_prodigy_test_api_url" => array('label' => __('TEST API URL', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $test_url
                                               ),
                   "wcfm_prodigy_live_api_key" => array('label' => __('LIVE API KEY', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $live_key ),
                   "wcfm_prodigy_live_api_url" => array('label' => __('LIVE API URL', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $live_url ),
                    "wcfm_prodigy_live_order_api_url" => array('label' => __('LIVE Order API URL', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $live_order_url ),
                    "wcfm_prodigy_oest_order_api_url" => array('label' => __('Test Order API URL', 'wc-frontend-manager'), 
                                               'type' => 'text', 
                                               'class' => 'wcfm-text  wcfm_ele', 
                                               'label_class' => 'wcfm_title wcfm_ele', 'value' => $live_order_url ),

               ),
           
           );
           ?>

            <?php
               do_action( 'wcfmd_prodigy_settings_after' );
           ?>

       </div>
   </div>
   <div class="wcfm_clearfix"></div>
   <!-- end collapsible -->

<?php
}

function wcfmt_prodigy_settings_update($wcfm_settings_form){
   global $WCFM, $WCFMgs, $_POST;
       // Save Enable Live
       $allow_live_api = isset( $wcfm_settings_form['wcfm_prodigy_live_mode'] ) ? $wcfm_settings_form['wcfm_prodigy_live_mode'] : 'no';
       wcfm_update_option( 'wcfm_prodigy_live_mode',  $allow_live_api );

       // Save Test Key
       if( isset( $wcfm_settings_form['wcfm_prodigy_test_api_key'] ) ) {
           $save_test_key = $wcfm_settings_form['wcfm_prodigy_test_api_key'];
           wcfm_update_option( 'wcfm_prodigy_test_api_key',  $save_test_key );
       }
       // Save TEST Url
       if( isset( $wcfm_settings_form['wcfm_prodigy_test_api_url'] ) ) {
           $save_test_url = $wcfm_settings_form['wcfm_prodigy_test_api_url'];
           wcfm_update_option( 'wcfm_prodigy_test_api_url',  $save_test_url );
       }
       // Save Live Key
       if( isset( $wcfm_settings_form['wcfm_prodigy_live_api_key'] ) ) {
           $save_live_key = $wcfm_settings_form['wcfm_prodigy_live_api_key'];
           wcfm_update_option( 'wcfm_prodigy_live_api_key',  $save_live_key );
       }
       // Save Live URL
       if( isset( $wcfm_settings_form['wcfm_prodigy_live_api_url'] ) ) {
           $save_live_url = $wcfm_settings_form['wcfm_prodigy_live_api_url'];
           wcfm_update_option( 'wcfm_prodigy_live_api_url',  $save_live_url );
       }
        // Save Live Order URL
        if( isset( $wcfm_settings_form['wcfm_prodigy_live_order_api_url'] ) ) {
            $save_live_order_url = $wcfm_settings_form['wcfm_prodigy_live_order_api_url'];
            wcfm_update_option( 'wcfm_prodigy_live_order_api_url',  $save_live_order_url );
        }
        // Save Test Order URL
        if( isset( $wcfm_settings_form['wcfm_prodigy_test_order_api_url'] ) ) {
            $save_test_order_url = $wcfm_settings_form['wcfm_prodigy_test_order_api_url'];
            wcfm_update_option( 'wcfm_prodigy_test_order_api_url',  $save_test_order_url );
        }

}



// Add support for Product Copy by Vendor

add_filter('wcfm_is_allow_product_multivendor_title_edit_disable', '__return_false');


 