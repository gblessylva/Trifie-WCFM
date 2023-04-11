<?php
global $WCFM, $wp_query;




?>


<div class="collapse wcfm-collapse" id="wcfm_brandsd_listing">
			
<div id="loader" class="lds-dual-ring hidden overlay"></div>
	
	<div class="wcfm-page-headig">
		<span class="fa fa-cubes"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Product Templates', 'wcfm-custom-menus' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		<?php do_action( 'before_wcfm_brands' ); ?>
		
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('Product Templates', 'wcfm-custom-menus' ); ?></h2>
			<div class="wcfm-clearfix"></div>

			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('edit.php?'); ?>" data-tip="<?php _e( 'WP Admin View', 'wcfm-custom-menus' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_cpt1_sub_menu', true ) ) {
				if ($allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true )) {
					echo '<a id="add_new_cpt1_dashboard" class="add_new_wcfm_ele_dashboard text_tip" href="'.get_site_url().'/store-manager/product-manage" data-tip="' . __('Add New ' , 'wcfm-custom-menus') . '"><span class="fa fa-cube"></span><span class="text">' . __( 'Add New', 'wcfm-custom-menus') . '</span></a>';
				}
				
			}
			?>
			<?php
			if ($allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true )) {
				$site_url = get_site_url();
				?>
			<a class="add_new_wcfm_ele_dashboard text_tip" href='<?php $site_url ?>/store-manager/product-template-upload'  data-tip="<?php _e( 'Import Product Termplates', 'wc-frontend-manager'); ?>"><span class="wcfmfa fa-upload upload-template"><span class="text hidden-text"><?php _e( 'Import Product Templates', 'wc-frontend-manager'); ?></span></span></a>
			<?php } ?>
			<?php	echo apply_filters( 'wcfm_cpt1_limit_label', '' ); ?>

	  </div>
	  <div class="wcfm-clearfix"></div><br />
		<div class="wcfm-container">
			<div id="wcfm_brandsd_listing_expander" class="wcfm-content">

				<!---- Add Content Here ----->
				<div id="grid-wrapper"></div>

				

				
				
				<!-- <div id="jsGrid"></div> -->



				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div>
		</div>
	
		<div class="wcfm-clearfix"></div>
		<?php
		do_action( 'after_wcfm_brands' );
		?>
	</div>
</div>

