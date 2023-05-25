<?php

function add_trifie_sku_metaboxes() {
	add_meta_box(
		'prodigi_sku',
		'Prodigi Custom Fields',
		'trifie_sku_fields',
		'trifie_sku',
		'normal',
		'default'
	);

}

add_action( 'add_meta_boxes', 'add_trifie_sku_metaboxes' );

function trifie_sku_fields() {
	global $post;
	// wp_nonce_field( basename( __FILE__ ), 'trifie_sku_recomended_size' );
    $prodigi_trifie_sku = get_post_meta( $post->ID, 'prodigi_trifie_sku', true );
    $trifie_product_id = get_post_meta( $post->ID, 'trifie_product_id', true );
	$trifie_sku_recomended_size = get_post_meta( $post->ID, 'trifie_sku_recomended_size', true );
    $trifie_product_min_price = get_post_meta( $post->ID, 'trifie_product_min_price', true );
    $trifie_product_cost = get_post_meta( $post->ID, 'trifie_product_cost', true );

    ?>
    <div class="custom-fields">
        <label for="prodigi_trifie_sku">Product Product SKU</label>
	    <input type="text" id='prodigi_trifie_sku' readonly name="prodigi_trifie_sku" value="<?php echo $prodigi_trifie_sku;  ?>" class="widefat">

        <label for="trifie_product_id">Trifie Product ID</label>
	    <input type="text" id='trifie_product_id' name="trifie_product_id" value="<?php echo $trifie_product_id ;  ?>" class="widefat">

        <label for="trifie_sku_recomended_size">Recommeded Size</label>
        <input type="text" name="trifie_sku_recomended_size" id="trifie_sku_recomended_size" value="<?php echo $trifie_sku_recomended_size; ?>"  class="widefat"/>
        
        <label for="trifie_product_min_price">Min Price</label>
        <input type="text" name="trifie_product_min_price" value="<?php echo $trifie_product_min_price; ?>" style="width:50%;" />

        <label for="trifie_product_cost">Cost</label>
        <input type="text" name="trifie_product_cost" value="<?php echo $trifie_product_cost; ?>" style="width:50%;" />

        
    </div>
       <?php

}



function trfie_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'trifie_product_cost',
        'trifie_product_min_price',
        'trifie_sku_recomended_size',
        'trifie_product_id',
        'prodigi_trifie_sku'
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'trfie_save_meta_box' );



//  function displayList(){
//     include "displaylist.php";
//  }

 add_action('admin_menu', 'prodigi_upload_sub_menu');


 function prodigi_upload_sub_menu(){

    add_submenu_page(
                    'edit.php?post_type=trifie_sku', //$parent_slug
                    'Upload Prodigi Products',  //$page_title
                    'Upload Prodigi Products',        //$menu_title
                    'manage_options',           //$capability
                    'upload_prodigi_products',//$menu_slug
                    'prodigi_upload_render_page'//$function
    );

}

//add_submenu_page callback function

function prodigi_upload_render_page() {

   include "includes/class_upload_prodigi_products.php";

}



  