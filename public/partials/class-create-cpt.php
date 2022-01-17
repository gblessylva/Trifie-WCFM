<?php
if ( ! function_exists('trifie_sku') ) {

    // Register Custom Post Type
    function trifie_sku() {
    
        $labels = array(
            'name'                  => _x( 'Trifie SKUs', 'Post Type General Name', 'trifie_sku' ),
            'singular_name'         => _x( 'Trifie SKU', 'Post Type Singular Name', 'trifie_sku' ),
            'menu_name'             => __( 'Trifie SKUs', 'trifie_sku' ),
            'name_admin_bar'        => __( 'Trifie SKU', 'trifie_sku' ),
            'archives'              => __( 'Trifie SKU Archives', 'trifie_sku' ),
            'attributes'            => __( 'Trifie SKU Attributes', 'trifie_sku' ),
            'parent_item_colon'     => __( 'Parent Item:', 'trifie_sku' ),
            'all_items'             => __( 'All Trifie SKUs', 'trifie_sku' ),
            'add_new_item'          => __( 'Add New Trifie SKU', 'trifie_sku' ),
            'add_new'               => __( 'Add New', 'trifie_sku' ),
            'new_item'              => __( 'New Trifie SKU', 'trifie_sku' ),
            'edit_item'             => __( 'Edit Trifie SKU', 'trifie_sku' ),
            'update_item'           => __( 'Update Trifie SKU', 'trifie_sku' ),
            'view_item'             => __( 'View Trifie SKU', 'trifie_sku' ),
            'view_items'            => __( 'View Trifie SKUs', 'trifie_sku' ),
            'search_items'          => __( 'Search Trifie SKU', 'trifie_sku' ),
            'not_found'             => __( 'Not found', 'trifie_sku' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'trifie_sku' ),
            'featured_image'        => __( 'Featured Image', 'trifie_sku' ),
            'set_featured_image'    => __( 'Set featured image', 'trifie_sku' ),
            'remove_featured_image' => __( 'Remove featured image', 'trifie_sku' ),
            'use_featured_image'    => __( 'Use as featured image', 'trifie_sku' ),
            'insert_into_item'      => __( 'Insert into item', 'trifie_sku' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'trifie_sku' ),
            'items_list'            => __( 'Trifie SKUs list', 'trifie_sku' ),
            'items_list_navigation' => __( 'Trifie SKUs list navigation', 'trifie_sku' ),
            'filter_items_list'     => __( 'Filter items list', 'trifie_sku' ),
        );
        $args = array(
            'label'                 => __( 'Trifie SKU', 'trifie_sku' ),
            'description'           => __( 'SKU List for Trifie', 'trifie_sku' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'custom-fields' ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-columns',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'taxonomies'          => array( 'sku_category' ),
        );
        register_post_type( 'trifie_sku', $args );
    
    }
    add_action( 'init', 'trifie_sku', 0 );
    
    }

// Register Custom Taxonomy
function trifie_sku_category() {

	$labels = array(
		'name'                       => _x( 'SKU Categories', 'Taxonomy General Name', 'trifie_sku_category' ),
		'singular_name'              => _x( 'SKU Category', 'Taxonomy Singular Name', 'trifie_sku_category' ),
		'menu_name'                  => __( 'SKU Category', 'trifie_sku_category' ),
		'all_items'                  => __( 'All SKU Categories', 'trifie_sku_category' ),
		'parent_item'                => __( 'Parent SKU Category', 'trifie_sku_category' ),
		'parent_item_colon'          => __( 'Parent SKU Category:', 'trifie_sku_category' ),
		'new_item_name'              => __( 'New SKU Category', 'trifie_sku_category' ),
		'add_new_item'               => __( 'Add SKU Category', 'trifie_sku_category' ),
		'edit_item'                  => __( 'Edit SKU Category', 'trifie_sku_category' ),
		'update_item'                => __( 'Update SKU Category', 'trifie_sku_category' ),
		'view_item'                  => __( 'View SKU Category', 'trifie_sku_category' ),
		'separate_items_with_commas' => __( 'Separate SKU Category with commas', 'trifie_sku_category' ),
		'add_or_remove_items'        => __( 'Add or remove SKU Category', 'trifie_sku_category' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'trifie_sku_category' ),
		'popular_items'              => __( 'Popular SKU Category', 'trifie_sku_category' ),
		'search_items'               => __( 'Search SKU Category', 'trifie_sku_category' ),
		'not_found'                  => __( 'Not Found', 'trifie_sku_category' ),
		'no_terms'                   => __( 'No SKU Category', 'trifie_sku_category' ),
		'items_list'                 => __( 'SKU Categories list', 'trifie_sku_category' ),
		'items_list_navigation'      => __( 'SKU Categories list navigation', 'trifie_sku_category' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
	);
	register_taxonomy( 'trifie_sku_category', array( 'trifie_sku' ), $args );

}
add_action( 'init', 'trifie_sku_category', 0 );
// Change Title
function trifie_sku_change_title_text( $title ){
    $screen = get_current_screen();
 
    if  ( 'trifie_sku' == $screen->post_type ) {
         $title = 'Prodigi Product (Original Name)';
    }
    return $title;
}
 
add_filter( 'enter_title_here', 'trifie_sku_change_title_text' );

function remove_trifie_page_metaboxes() {
    remove_meta_box( 'postcustom','trifie_sku','high' ); // Custom Fields Metabox
    
    }
add_action('admin_menu', 'remove_trifie_page_metaboxes');


function my_remove_wp_seo_meta_box() {
	remove_meta_box('wpseo_meta', 'trifie_sku', 'normal');
    
}
add_action('add_meta_boxes', 'my_remove_wp_seo_meta_box', 100);

function trifie_sku_remove_support() {
    remove_post_type_support( 'trifie_sku', 'editor' );
}
add_action( 'init', 'trifie_sku_remove_support', 10 );


// Set Custom Columns

function set_custom_edit_trifie_sku_columns($columns) {
    unset($columns['author']);
    unset($columns['date']);
    unset($columns['comments']);
    unset($columns['tags']);
    unset($columns['wpseo-score']);
    $columns['prodigi_trifie_sku'] = __('Prodigi SKU', 'trifie_sku');
    $columns['trifie_sku_recomended_size'] = __('Recomended Size', 'trifie_sku');
    $columns['trifie_product_id'] = __('Trifie Product ID', 'trifie_sku');
    $columns['trifie_product_cost'] = __('Product Cost', 'trifie_sku');
    $columns['trifie_product_min_price'] = __('Minimum Price', 'trifie_sku');
    return $columns;

}   
    
    add_action('manage_trifie_sku_posts_columns', 'set_custom_edit_trifie_sku_columns');

    // Render Custom Columns
    function fill_trifie_sku_columns($column) {
        global $post;
        $post_id = $post->ID;
        switch ($column) {
            case 'prodigi_trifie_sku' :
                $prodigi_trifie_sku = get_post_meta($post_id, 'prodigi_trifie_sku', true);
                echo $prodigi_trifie_sku;
                break;
            case 'trifie_sku_recomended_size' :
                $trifie_sku_recomended_size = get_post_meta($post_id, 'trifie_sku_recomended_size', true);
                echo $trifie_sku_recomended_size;
                break; 
            case 'trifie_product_id' : 
                $trifie_product_id = get_post_meta($post_id, 'trifie_product_id', true);
                echo $trifie_product_id;
                break;
            case 'trifie_product_cost' : 
                $trifie_product_cost = get_post_meta($post_id, 'trifie_product_cost', true);
                echo $trifie_product_cost;
                break;
            case 'trifie_product_min_price' : 
                $trifie_product_min_price = get_post_meta($post_id, 'trifie_product_min_price', true);
                echo $trifie_product_min_price;
                break;
            
        }
    }
    add_action('manage_trifie_sku_posts_custom_column', 'fill_trifie_sku_columns');

    // Make Columns Sortable

    function trifie_sku_sortable_columns() {
        return array(
            'prodigi_trifie_sku' => 'prodigi_trifie_sku',
            'trifie_sku_recomended_size' => 'trifie_sku_recomended_size',
            'trifie_product_id' => 'trifie_product_id',
            'trifie_product_cost' => 'trifie_product_cost',
            'trifie_product_min_price' => 'trifie_product_min_price',
            'title' => 'title',
            'taxonomy-trifie_sku_category' => 'taxonomy-trifie_sku_category'
        );
    }
    add_filter('manage_edit-trifie_sku_sortable_columns', 'trifie_sku_sortable_columns');


// Load Products on Table


function load_cpt_script($endpoint)
{
    global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
    wp_enqueue_script( 'load_cpt', $plugin_url .'../js/load-spt.js', array( 'jquery' ), $WCFM->version, true );
    wp_localize_script( 'load_cpt', 'CPTAjax', array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                  ));
}

add_action('wp_enqueue_scripts', 'load_cpt_script');


add_action('wp_ajax_load_cpt_products', 'load_cpt_products');
add_action('wp_ajax_no_priv_load_cpt_products','load_cpt_products');

function load_cpt_products(){
    global $wpdb;
  //some basic query arguments
  $posts_per_page = -1;
  $args = array(
    'post_type'             => 'trifie_sku',
    'posts_per_page'        => $posts_per_page,
  );
  //query
  $postsQ = new WP_Query( $args );
 
  //create empty array and loop through results, populating array
  $return_json = array();
  $index = 0;
  while($postsQ->have_posts()) {
    $url = get_the_permalink(get_the_ID());
    $postsQ->the_post();
    // $actions = '<a class="wcfm-action-icon" href="#"><span class="fa fa-copy clone-template text_tip" data-id= "'.get_the_ID().'" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span></a>';
    $actions = '<a class="wcfm-action-icon " target="_blank" href="'.site_url().'/store-manager/trifie_sku-manage/'.get_the_ID().'"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit Template', 'wcfm-cpt' ) . '"></span></a>';;
    $actions .= '<a class="wcfm-action-icon " target="_blank" href="'.site_url().'/store-manager/products-manage?prodigi-id='.get_the_ID().'"><span class="fa fa-clone text_tip" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span></a>';
    $link = '<a href="'.$url.'" target="_blank">'. get_the_title().' </a>';
    
    $prodigi_trifie_sku = get_post_meta(get_the_ID(), 'prodigi_trifie_sku', true);
    $trifie_sku_recomended_size= get_post_meta(get_the_ID(), 'trifie_sku_recomended_size', true);
    $trifie_product_cost =get_post_meta(get_the_ID(), 'trifie_product_cost', true);
    $trifie_product_min_price= get_post_meta(get_the_ID(), 'trifie_product_min_price', true);

    $return_json[$index][] = $link;
    $return_json[$index][] = $prodigi_trifie_sku;
    $return_json[$index][] = $trifie_sku_recomended_size;
    $return_json[$index][]= $trifie_product_cost;
    $return_json[$index][]= $trifie_product_min_price;
    $return_json[$index][]= $trifie_product_min_price;
    $return_json[$index][]=$actions;
    
    $index++;

    
  }
  //return the result to the ajax request and die
  echo json_encode(array("recordsTotal"=>$postsQ->found_posts, 'data' => $return_json));
  wp_die();

}



