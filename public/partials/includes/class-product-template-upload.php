<?php
global $WCFM, $wp_query;

?>


<div class="collapse wcfm-collapse" id="wcfm_brandsd_listing">
			
<div id="loader" class="lds-dual-ring hidden overlay"></div>
	
	<div class="wcfm-page-headig">
		<span class="fa fa-cubes"></span>
		<span class="wcfm-page-heading-text"><?php _e('Prodigi Templates', 'wcfm-custom-menus' ); ?></span>
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
				<?php
					do_action( 'before-template-form' );
					?>
                <form class="form-horizontal" action="" method="post"
                name="frmCSVImport" id="frmCSVImport"
                enctype="multipart/form-data">
                <div class="input-row">
                    <label class="col-md-4 control-label">Choose CSV
                        File</label> <input type="file" name="template-file"
                        id="file" accept=".csv">
                    <button type="submit" id="submit" name="import-template"
                        class="btn-submit">Import</button>
                    <br />

                </div>

            </form>
			<div class="table-responsive" id="process_area">
					
					
					
					
<?php
						
if(isset($_POST['import-template'])){
  $handle = fopen($_FILES['template-file']['tmp_name'], "r");
  $headers = fgetcsv($handle, 1000, ",");
  global $current_user;
  wp_get_current_user() ;
  $username = $current_user->user_login;

 while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) 
  {

      $product_name = trim( $row[0]);
      $short_description = trim( $row[1]);
      $long_description = trim($row[2]);
      $category = trim($row[3]);
      $printable_sku = trim( $row[4]);
      $sku = trim($row[5]);
      $min_price = trim( $row [6]);
      $price = trim($row[7]);
      $product_template = trim($row[8]);
	  $attributes = trim($row[9]);
      $slug = strtolower(str_replace(' ', '-', $product_name));
	  $template_cat_slug = strtolower(str_replace(' ', '-', $category));
	  $updated_sku = $username .'-'.$sku.'-';

	  
	  $taxonomy = 'product_cat';
	  $template_cat = get_term_by('name', $category, $taxonomy);
	  
	  if($template_cat == false){
		$cat = wp_insert_term($template_cat_slug, $taxonomy);
		$cat_id = $cat['term_id'] ;
	  }else{
		$cat_id = $template_cat->term_id ;
	  }
    //   var_dump($cat_id);
      $template_id =wp_insert_post(array(
          'post_title' => $product_name,
          'post_content' => $long_description,
		  'post_excerpt'=> $short_description,
          'post_type' => 'product',
          'post_status' => 'template'
      ), $wp_error);

	  if($template_id){
		wp_set_post_terms($template_id, array($cat_id), $taxonomy, true);

		update_post_meta($template_id, '_sku', $updated_sku);
		update_post_meta($template_id, '_printable_sku', $printable_sku);
		update_post_meta($template_id, '_price', $price);
		update_post_meta($template_id, '_regular_price', $price);
		update_post_meta($template_id, 'trifie_product_min_price', $min_price);
		update_post_meta($template_id, '_is_product_tamplate', 'enable');
	  }

	$attributes_array = explode(',', $attributes);
	for ($i = 0; $i < count($attributes_array); $i++) {
		$new_attributes = explode('|', $attributes_array[$i]);
		$attribute_name = array_shift($new_attributes);

		$attribute_value = $new_attributes;

		$attribute_name = 'pa_'. strtolower( $attribute_name);		
		
		foreach ($attribute_value as $value) {
			
			$attribute_value_id = wc_sanitize_taxonomy_name($value);
			wp_set_object_terms($template_id, $attribute_value_id, $attribute_name, true);

			$updated_attributes = array(
			$attribute_name =>array(
			'name' => $attribute_name,
			'value' => $attribute_value_id,
			'visible' => '1',
			'taxonomy' => '1',
			));

			// $_product_attributes = get_post_meta($template_id, '_product_attributes', TRUE);
			//Updating the Post Meta
			// $res = update_post_meta($template_id, '_product_attributes', array_merge( $updated_attributes));
			var_dump($updated_attributes);
			$res= update_post_meta( $template_id, '_product_attributes', $updated_attributes);
			var_dump($res);
			
		  }
		 
	}
	

	  }
    //   echo $template_id;


}


					?>
            </div>
			<table>

			</table>


			<?php
					do_action( 'after-template-form' );
				?>

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

