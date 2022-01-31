<?php
global $WCFM, $wp_query;




?>


<div class="collapse wcfm-collapse" id="wcfm_brandsd_listing">
			
<div id="loader" class="lds-dual-ring hidden overlay"></div>
	
	<div class="wcfm-page-headig">
		<span class="fa fa-cubes"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Prodigi Templates', 'wcfm-custom-menus' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
		<div id="wcfm_page_load"></div>
		<?php do_action( 'before_wcfm_brands' ); ?>
		
		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('Upload Prodigi Templates', 'wcfm-custom-menus' ); ?></h2>
			<div class="wcfm-clearfix"></div>

			

	  </div>
	  <div class="wcfm-clearfix"></div><br />
		<div class="wcfm-container">
			<div id="wcfm_brandsd_listing_expander" class="wcfm-content">

				<!---- Add Content Here ----->
				
                

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

