<?php
/**
 * WCFM plugin view
 *
 * WCFM CPT1 Manage Tabs view
 *
 * @author 		WC Lovers
 * @package 	wcfmcpt/views/cpt1
 * @version   1.0.0
 */
 
global $wp, $WCFM, $WCFMcpt;


$trifie_sku_recomended_size = '';
$prodigi_trifie_sku = '';
$trifie_product_id = '';
$trifie_product_cost = '';
$trifie_product_min_price ='';

if( $cpt1_id ) {
	// Custom Meta Fields
	$trifie_sku_recomended_size = get_post_meta( $cpt1_id, 'trifie_sku_recomended_size', true);
	$prodigi_trifie_sku = get_post_meta( $cpt1_id, 'prodigi_trifie_sku', true);
	$trifie_product_id = get_post_meta( $cpt1_id, 'trifie_product_id', true);
	$trifie_product_cost = get_post_meta( $cpt1_id, 'trifie_product_cost', true);
	$trifie_product_min_price = get_post_meta($cpt1_id, 'trifie_product_min_price', true);
}

?>
<!-- collapsible 1 -->
<div class="page_collapsible cpt1_manager_custom" id="wcfm_cpt1_manager_form_custom_head"><label class="fa fa-file"></label><?php _e('Product Details', 'wcfm-cpt'); ?><span></span></div>
<div class="wcfm-container">
	<div id="wcfm_cpt1_manager_form_custom_expander" class="wcfm-content">
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_cpt1_fields_custom', array(
																																														"trifie_sku_recomended_size" => array('label' => __('Recomended Size', 'wcfm-cpt') , 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $trifie_sku_recomended_size, 'hints' => __( 'Please Enter the recomnedded size by Prodigi', 'wcfm-cpt' )),
																																														"prodigi_trifie_sku" => array('label' => __('Prodigi SKU', 'wcfm-cpt') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $prodigi_trifie_sku, 'label_class' => 'wcfm_title wcfm_ele', 'hints' => __('Prodigi Product SKU', 'wcfm-cpt'), 'required'=>true),
																																														"trifie_product_id" => array('label' => __('Trifie Product ID', 'wcfm-cpt') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele simple variable non_manage_stock_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $trifie_product_id, 'hints' => __( 'Unique Product Identifier.', 'wcfm-cpt' ), 'attributes' => array( 'required' => true ) ),
																																														"trifie_product_cost" => array('label' => __('Product Cost', 'wcfm-cpt') , 'type' => 'number', 'class' =>'wcfm-text wcfm_ele simple variable non_manage_stock_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $trifie_product_cost, 'hints' => __( 'Enter Product Cost', 'wcfm-cpt' ), 'attributes' => array( 'min' => '1', 'required'=>true, 'step'=> '1' ) ),
																																														"trifie_product_min_price" => array('label' => __('Min Cost', 'wcfm-cpt') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele simple variable non_manage_stock_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $trifie_product_min_price, 'hints' => __( 'Minimum Product cost', 'wcfm-cpt' ), 'attributes' => array( 'min' => '1', 'required'=>true, 'step'=> '1' ) ),

																																										), $cpt1_id ) );
		?>
	</div>
</div>
<!-- end collapsible -->

<!-- collapsible 2 -->
<div class="page_collapsible products_manage_attribute <?php echo $wcfm_pm_block_class_attributes; ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_attributes', '' ); ?>" id="wcfm_products_manage_form_attribute_head"><label class="wcfmfa fa-server"></label><?php _e('Attributes', 'wc-frontend-manager'); ?><span></span></div>
				<div class="wcfm-container <?php echo $wcfm_pm_block_class_attributes; ?> <?php echo apply_filters( 'wcfm_pm_block_custom_class_attributes', '' ); ?>">
					<div id="wcfm_products_manage_form_attribute_expander" class="wcfm-content">
					  <?php do_action( 'wcfm_products_manage_attributes_start', $product_id, $product_type ); ?>
						<?php
						//   do_action( 'wcfm_products_manage_attributes', $product_id );
						  
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'product_simple_fields_attributes', array(  
																																															"attributes" => array( 'label' => __( 'Attributes', 'wc-frontend-manager' ), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_input_attributes wcfm_ele simple variable external grouped booking', 'has_dummy' => true, 'label_class' => 'wcfm_title', 'value' => $attributes, 'options' => array(
																																																	"term_name" => array('type' => 'hidden'),
																																																	"is_active" => array('label' => __('Active?', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele attribute_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking checkbox_title'),
																																																	"name" => array('label' => __('Name', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele attribute_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking'),
																																																	"value" => array('label' => __('Value(s):', 'wc-frontend-manager'), 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele simple variable external grouped booking', 'placeholder' => sprintf( __('Enter some text, some attributes by "%s" separating values.', 'wc-frontend-manager'), WC_DELIMITER ), 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking'),
																																																	"is_visible" => array('label' => __('Visible on the product page', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking checkbox_title'),
																																																	"is_variation" => array('label' => __('Use as Variation', 'wc-frontend-manager'), 'type' => 'checkbox', 'value' => 'enable', 'class' => 'wcfm-checkbox wcfm_ele variable variable-subscription', 'label_class' => 'wcfm_title checkbox_title wcfm_ele variable variable-subscription'),
																																																	"tax_name" => array('type' => 'hidden'),
																																																	"is_taxonomy" => array('type' => 'hidden')
																																															) )
																																										), $product_id ) );
						?>
						<div class="wcfm_clearfix"></div><br />
						<div class='spacer' style="margin-bottom: 100px">
							<?php if( apply_filters( 'wcfm_is_allow_add_attribute', true ) ) { ?>
								<select name="wcfm_attribute_taxonomy" class="wcfm-select wcfm_attribute_taxonomy">
									<option value="add_attribute"><?php _e( 'Add attribute', 'wc-frontend-manager' ); ?></option>
								</select>
								<button type="button" class="button wcfm_add_attribute"><?php _e( 'Add', 'wc-frontend-manager' ); ?></button>
							<?php } ?>
						</div>
						
						<?php do_action( 'wcfm_products_manage_attributes_end', $product_id, $product_type ); ?>
						
						<div class="wcfm_clearfix"></div><br />
						<h1></h1>
					</div>
				</div>
<!-- end collapsible -->
<div class="wcfm_clearfix"></div>