<?php
function wcfmcsm_query_vars( $query_vars ) {
	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
	
	$query_custom_menus_vars = array(
		'prodigi-templates'               => ! empty( $wcfm_modified_endpoints['prodigi-templates'] ) ? $wcfm_modified_endpoints['prodigi-templates'] : 'prodigi-templates',
		
		'manage-prodigi-products'             => ! empty( $wcfm_modified_endpoints['manage-prodigi-products'] ) ? $wcfm_modified_endpoints['manage-prodigi-products'] : 'manage-prodigi-products',
		// 'add-brand'             => ! empty( $wcfm_modified_endpoints['add-brand'] ) ? $wcfm_modified_endpoints['add-brand'] : 'add-brand'
	);
	
	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );
	
	return $query_vars;
}
add_filter( 'wcfm_query_vars', 'wcfmcsm_query_vars', 50 );

/**
 * WCFM - Custom Menus End Point Title
 */
function wcfmcsm_endpoint_title( $title, $endpoint ) {
	global $wp;
	switch ( $endpoint ) {
		case 'prodigi-templates' :
			$title = __( 'Prodigi Templates', 'wcfm-custom-menus' );
		break;
		case 'manage-prodigi-products' :
			$title = __( 'manage-prodigi-products', 'wcfm-custom-menus' );
		break;
		// case 'add-brand' :
		// 	$title = __("Add New Brand", 'wcfm-custom-menu');
		// break;	

	}
	
	return $title;
}
add_filter( 'wcfm_endpoint_title', 'wcfmcsm_endpoint_title', 50, 2 );

/**
 * WCFM - Custom Menus Endpoint Intialize
 */
function wcfmcsm_init() {
	global $WCFM_Query;

	// Intialize WCFM End points
	$WCFM_Query->init_query_vars();
	$WCFM_Query->add_endpoints();
	
	if( !get_option( 'wcfm_updated_end_point_cms' ) ) {
		// Flush rules after endpoint update
		flush_rewrite_rules();
		update_option( 'wcfm_updated_end_point_cms', 1 );
	}
}
add_action( 'init', 'wcfmcsm_init', 50 );

/**
 * WCFM - Custom Menus Endpoiint Edit
 */
function wcfm_custom_menus_endpoints_slug( $endpoints ) {
	
	$custom_menus_endpoints = array(
												'prodigi-templates'        => 'prodigi-products',
												
												'manage-prodigi-products'      => 'manage-prodigi-products',
												// 'add-brand' => 'add-brand',
												);
	
	$endpoints = array_merge( $endpoints, $custom_menus_endpoints );
	
	return $endpoints;
}
add_filter( 'wcfm_endpoints_slug', 'wcfm_custom_menus_endpoints_slug' );

if(!function_exists('get_wcfm_custom_menus_url')) {
	function get_wcfm_custom_menus_url( $endpoint ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_custom_menus_url = wcfm_get_endpoint_url( $endpoint, '', $wcfm_page );
		return $wcfm_custom_menus_url;
	}
}

/**
 * WCFM - Custom Menus
 */
function wcfmcsm_wcfm_menus( $menus ) {
	global $WCFM;
	
	$custom_menus = array( 'prodigi-templates' => array(   'label'  => __( 'Prodigi Templates', 'wcfm-custom-menus'),
																									 'url'       => get_wcfm_custom_menus_url( 'prodigi-templates' ),
																									 'icon'      => 'cube',
																									 'priority'  => 5.1
																									)
											);
	
	$menus = array_merge( $menus, $custom_menus );
		
	return $menus;
}
add_filter( 'wcfm_menus', 'wcfmcsm_wcfm_menus', 20 );

/**
 *  WCFM - Custom Menus Views
 */
function wcfm_csm_load_views( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_path = trailingslashit( dirname( __FILE__  ) );
	
	switch( $end_point ) {
		case 'prodigi-templates':
            // require_once( 'includes/class-view-all-products.php');
            require_once('includes/class-test-view.php');
         
		break;

		case 'manage-prodigi-products':
			// require_once( $plugin_path . 'views/class-view-all-products.php' );
		break;
		// case 'add-brand' :
		// 	require_once($plugin_path . 'views/add_brand.php');
		// 	break;
	}
}
add_action( 'wcfm_load_views', 'wcfm_csm_load_views', 50 );
add_action( 'before_wcfm_load_views', 'wcfm_csm_load_views', 50 );

// Custom Load WCFM Scripts
function wcfm_csm_load_scripts( $end_point ) {
	global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
	
	switch( $end_point ) {
		case 'prodigi-templates':
			wp_enqueue_script( 'wcfm_brands_js',  $plugin_url .'../js/load-table.js', array( 'jquery' ), $WCFM->version, true );
            wp_enqueue_script('jquery-datatables-js','//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js',array('jquery'));
            wp_enqueue_script('jquery-datatables-responsive-js','//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js',array('jquery'));
            wp_enqueue_script('table', 'https://table-sortable.now.sh/table-sortable.js', array( 'jquery' ), $WCFM->version, true);

            wp_enqueue_script( 'clone-template', $plugin_url .'../js/clone-template.js', array( 'jquery' ), $WCFM->version, true );
            wp_localize_script( 'clone-template', 'CloneAjax', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
              ));
		break;
	}
}

add_action( 'wcfm_load_scripts', 'wcfm_csm_load_scripts' );
add_action( 'after_wcfm_load_scripts', 'wcfm_csm_load_scripts' );

// Custom Load WCFM Styles
function wcfm_csm_load_styles( $end_point ) {
	global $WCFM, $WCFMu;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
	
	switch( $end_point ) {
		case 'prodigi-templates':
			// wp_enqueue_style( 'wcfmu_brands_css', $plugin_url . '../css/product.css', array(), $WCFM->version );
            wp_enqueue_style('jquery-datatables-css','//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
            wp_enqueue_style('jquery-datatables-responsive-css','//cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css');

            
		break;
	}
}
add_action( 'wcfm_load_styles', 'wcfm_csm_load_styles' );
add_action( 'after_wcfm_load_styles', 'wcfm_csm_load_styles' );



add_action('wp_ajax_datatables_endpoint', 'prodigi_load_templates'); //logged in
add_action('wp_ajax_no_priv_datatables_endpoint', 'prodigi_load_templates'); //not logged in


function prodigi_load_templates(){
    global $wpdb;

    $response = []; 
        
    //Get WordPress posts - you can get your own custom posts types etc here
    $posts = get_posts([
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key'     => '_post_dwp',
                'compare' => 'NOT EXISTS',
            ),
        )
    ]);
    
   
   
    if(!empty($posts)){
        $response['recordsTotal'] = !empty($posts) ? count($posts) : 0;

        //Loop through the posts and add them to the response
        foreach($posts as $post){
            $term = get_terms($args = array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'fields' => 'names',
                'object_ids' => $post->ID,
            ));
            
            $tag =get_terms($args = array(
                'taxonomy' => 'product_tag',
                'hide_empty' => false,
                'fields' => 'names',
                'object_ids' => $post->ID,
            ));

            $nonce = wp_create_nonce('clone_template_nonce');
            $action = '<a href="#"  class="wcfm_action_icon"> <span class="wcfmfa  fa-copy clone-template  text_tip" data-id="'.$post->ID.'" data-nonce = "'.__($nonce).'" data-tip="'.__('Clone Template', 'wc-frontend-manager').'"></span></a> ';

            //Add two properties to our response - 'data' and 'recordsTotal'
            $row = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'content' => $post->post_content,
                'link' => get_permalink($post->ID),
                'image' => get_the_post_thumbnail_url($post->ID,'full'),
                'price' => get_post_meta($post->ID, '_regular_price', true),
                'sale_price' => get_post_meta($post->ID, '_sale_price', true),
                'stock' => get_post_meta($post->ID, '_stock', true),
                'sku' => get_post_meta($post->ID, '_sku', true),
                'categories' =>  $term[0],
                'tags' => $tag[0],
                'attributes' => get_post_meta($post->ID, '_product_attributes', true),
                'type' => get_post_meta($post->ID, '_product_type', true),
                'weight' => get_post_meta($post->ID, '_weight', true),
                'length' => get_post_meta($post->ID, '_length', true),
                'width' => get_post_meta($post->ID, '_width', true),
                'height' => get_post_meta($post->ID, '_height', true),
                'tax_status' => get_post_meta($post->ID, '_tax_status', true),
                'tax_class' => get_post_meta($post->ID, '_tax_class', true),
                'shipping_class' => get_post_meta($post->ID, '_shipping_class', true),
                'author' => get_the_author_meta( 'nicename', $post->post_author ),
                'date' => get_the_date('Y-m-d'),
                'prodigi_sku' => get_post_meta($post->ID, '_printable_sku', true),
                'action' => $action
            );
            $response['data'][] = $row;
        }
        
    }

    // $response['data'] = !empty($posts) ? $posts : []; //array of post objects if we have any, otherwise an empty array        
    // $response['recordsTotal'] = !empty($posts) ? count($posts) : 0; //total number of posts without any filtering applied
    
    wp_send_json($response); //json_encodes our $response and sends it back with the appropriate headers
    
}

add_action('wp_ajax_clone_prodigi_template', 'clone_prodigi_template');
add_action('wp_ajax_no_priv_clone_prodigi_template','clone_prodigi_template');


function clone_prodigi_template(){
    global $wpdb;
    $post_id = $_POST['id'];
    if (isset($_POST['id'])){
        $id = $_POST['id'];
    }else{
        $id = "";
    }

    global $post;
    $response = [];
    $author = get_current_user();

    $post = get_post($post_id);
    $prodigi_sku = get_post_meta($post_id, '_printable_sku', true);
    $new_product = array(
                'post_content'          => $post->post_content,
                'post_title'            => $post->post_title .'-Copy',
                'post_name'             => $post->post_name . '-copy',
                'author' => $author,
                'post_excerpt'          => $post->post_excerpt,
                'post_status'           => 'draft',
                'comment_status'        => $post->comment_status,
                'ping_status'           => $post->ping_status,
                'post_password'         => $post->post_password,
                // 'post_name'          => $post->post_name,
                'to_ping'               => $post->to_ping,
                'pinged'                => $post->pinged,
                'post_content_filtered' => $post->post_content_filtered,
                'post_parent'           => $post->post_parent,
                'menu_order'            => $post->menu_order,
                'post_type'             => $post->post_type,
                'post_mime_type'        => $post->post_mime_type
            );

        // Create new Product
        $new_product_id = wp_insert_post($new_product);
        // Update Custom Taxonomies
        $taxonomies = get_object_taxonomies('product'); 
       
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_product_id, $post_terms, $taxonomy, false);
        }


            // Duplicate Product Meta

        $store_user = wcfmmp_get_store();
        $author = $store_user->data->user_nicename;
        $prodigi = get_post_meta($post_id, '_printable_sku', true);
          
        $prodigi_product = get_posts(array(
            'numberposts'   => 1,
            'post_type'     => 'trifie_sku',
            'meta_key'      => 'prodigi_trifie_sku',
           'meta_value'    => $prodigi
        ));
              
        $argt = array(
            'post_type'=> 'product',
            'author' => $store_user->data->ID,
            'status'=> array('published', 'draft', )
        );

        $show_posts = new WP_Query($argt);
        $count = $show_posts->found_posts +1;

            $prodigi_product_id =$prodigi_product[0]->ID;
            // $total_vendor_products = count_user_posts( $store_user->data->ID, 'product' ) + 1;
            $trife_product_id = get_post_meta($prodigi_product_id, 'trifie_product_id', true);
            $generated_sku = $author .'-' .$trife_product_id. '-' . $count;

        $data = get_post_custom($post_id);
            foreach ( $data as $key => $values) {
            foreach ($values as $value) {
                update_post_meta( $new_product_id, $key, maybe_unserialize( $value ) );// it is important to unserialize data to avoid conflicts.
            }
            }
        
        update_post_meta($new_product_id, '_sku', $generated_sku);
        $results = get_post($new_product_id);
        $result_meta = $trife_product_id;
        $homeurl = get_home_url();
        echo json_encode(array('success' => true, 'message'=>'Product Cloned will redirect shortly', 'result' => $results, 'homeurl'=>$homeurl, 'product_id' => $new_product_id));

    exit;
}

