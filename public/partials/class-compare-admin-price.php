<?php
global $wp, $WCFM;



add_filter( 'wcfm_form_custom_validation', 'set_admin_price_compare' , 50 , 2 );

function set_admin_price_compare( $wcfm_products_manage_form_data, $form_manager ) {
    if( $form_manager == 'product_manage' ) {
       
        $user = wp_get_current_user();
    if($user->roles !=['administrator']){
            
        if( $wcfm_products_manage_form_data['product_type'] == 'simple' ) {
            $regular_price = isset( $wcfm_products_manage_form_data['regular_price'] ) ? wc_clean( $wcfm_products_manage_form_data['regular_price'] ) : '';
            $_admin_min_price = isset( $wcfm_products_manage_form_data['_admin_min_price'] ) ? wc_clean( $wcfm_products_manage_form_data['_admin_min_price'] ) : '';
            
            if( !$_admin_min_price || ( $_admin_min_price < 0 ) ) {
                $custom_validation_results['has_error'] = true;
                $custom_validation_results['message'] = 'The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください';
                return $custom_validation_results;
            }
            
            if( $regular_price && ( $regular_price <= $_admin_min_price ) ) {
                $custom_validation_results['has_error'] = true;
                $custom_validation_results['message'] = 'The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください';
                return $custom_validation_results;
            }
            if( $regular_price = '' ) {
                $custom_validation_results['has_error'] = true;
                $custom_validation_results['message'] = 'The Price must be greater than 0. / 商品価格（Price）は0より大きい数字にしてください';
                return $custom_validation_results;
            }
        } elseif( $wcfm_products_manage_form_data['product_type'] == 'variable' ) {
            if(isset($wcfm_products_manage_form_data['variations']) && !empty($wcfm_products_manage_form_data['variations'])) {
              foreach($wcfm_products_manage_form_data['variations'] as $variations) {
                  $regular_price = isset( $variations['regular_price'] ) ? wc_clean( $variations['regular_price'] ) : '';
                    $_admin_min_price = isset( $variations['_admin_min_price'] ) ? wc_clean( $variations['_admin_min_price'] ) : '';
                    
                    if( !$_admin_min_price || ( $_admin_min_price < 0 ) ) {
                        $custom_validation_results['has_error'] = true;
                        $custom_validation_results['message'] = 'Admin Price Cannot be Zero';
                        return $custom_validation_results;
                    }
                    
                    if( $regular_price && ( $regular_price <= $_admin_min_price ) ) {
                        $custom_validation_results['has_error'] = true;
                        $custom_validation_results['message'] = 'The Price must be greater than the Minimum Price. / 商品価格（Price）は最低価格（Min Product Price）より大きい数字にしてください';
                        return $custom_validation_results;
                    }
              }
            }
        }

        }
            }
    return $wcfm_products_manage_form_data;
  }