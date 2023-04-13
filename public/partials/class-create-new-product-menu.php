<?php
function trifie_query_vars( $query_vars ) {
	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
	
	$query_custom_menus_vars = array(
		'product-templates'               => ! empty( $wcfm_modified_endpoints['product-templates'] ) ? $wcfm_modified_endpoints['product-templates'] : 'product-templates',
		
		'manage-prodigi-products'             => ! empty( $wcfm_modified_endpoints['manage-prodigi-products'] ) ? $wcfm_modified_endpoints['manage-prodigi-products'] : 'manage-prodigi-products',
	     'product-template-upload'             => ! empty( $wcfm_modified_endpoints['product-template-upload'] ) ? $wcfm_modified_endpoints['product-template-upload'] : 'product-template-upload'
	);
	
	$query_vars = array_merge( $query_vars, $query_custom_menus_vars );
	
	return $query_vars;
}
add_filter( 'wcfm_query_vars', 'trifie_query_vars', 50 );

/**
 * WCFM - Custom Menus End Point Title
 */
function wcfmcsm_endpoint_title( $title, $endpoint ) {
	global $wp;
	switch ( $endpoint ) {
		case 'product-templates' :
			$title = __( 'Product Templates', 'wcfm-custom-menus' );
		break;
		case 'manage-prodigi-products' :
			$title = __( 'manage-prodigi-products', 'wcfm-custom-menus' );
		break;
		// case 'product-template-upload' :
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
												'product-templates'        => 'prodigi-products',
												
												'manage-prodigi-products'      => 'manage-prodigi-products',
												// 'product-template-upload' => 'product-template-upload',
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
	
	$custom_menus = array( 'product-templates' => array(   'label'  => __( 'Product Templates', 'wcfm-custom-menus'),
																									 'url'       => get_wcfm_custom_menus_url( 'product-templates' ),
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
		case 'product-templates':
            //  require_once( 'includes/class-view-all-products.php');
            require_once('includes/class-test-view.php');
         
		break;

		case 'manage-prodigi-products':
			// require_once( $plugin_path . 'views/class-view-all-products.php' );
		break;
		case 'product-template-upload' :
			require_once($plugin_path . 'includes/class-product-template-upload.php');
			break;
	}
}
add_action( 'wcfm_load_views', 'wcfm_csm_load_views', 50 );
add_action( 'before_wcfm_load_views', 'wcfm_csm_load_views', 50 );

// Custom Load WCFM Scripts
function wcfm_csm_load_scripts( $end_point ) {
	global $WCFM;
	$plugin_url = trailingslashit( plugins_url( '', __FILE__ ) );
	
	switch( $end_point ) {
		case 'product-templates':
            wp_enqueue_script('gridTable', 'https://unpkg.com/gridjs/dist/gridjs.umd.js', array( 'jquery' ), $WCFM->version, true);
			wp_enqueue_script( 'wcfm_brands_js',  $plugin_url .'../js/load-table.js', array( 'jquery' ), $WCFM->version, true );

            wp_enqueue_script('jquery-datatables-js','//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js',array('jquery'));
            wp_enqueue_script('jquery-datatables-responsive-js','//cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js',array('jquery'));
            wp_enqueue_script('table', 'https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js', array( 'jquery' ), $WCFM->version, true);
           

            wp_enqueue_script( 'clone-template', $plugin_url .'../js/clone-template.js', array( 'jquery' ), $WCFM->version, true );
            wp_localize_script( 'clone-template', 'CloneAjax', array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
              ));

// Load delete script

wp_enqueue_script( 'delete-product-template', $plugin_url .'../js/delete-product-template.js', array( 'jquery' ), $WCFM->version, true );
wp_localize_script( 'delete-product-template', 'DeleteAjax', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
  ));
            
		break;
        case 'product-template-upload' :
            wp_enqueue_script( 'template-upload-checker',  $plugin_url .'../js/template-upload-checker.js', array( 'jquery' ), $WCFM->version, true );
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
		case 'product-templates':
			// wp_enqueue_style( 'wcfmu_brands_css', $plugin_url . '../css/product.css', array(), $WCFM->version );
            wp_enqueue_style('jquery-datatables-css','//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css');
            wp_enqueue_style('jquery-datatables-responsive-css','//cdn.datatables.net/responsive/2.2.6/css/responsive.dataTables.min.css');

            wp_enqueue_style('gritStyles','https://unpkg.com/gridjs/dist/theme/mermaid.min.css');

            wp_enqueue_style('jquery-tables-responsive-css','https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css');
            wp_enqueue_style('jquery-tables-responsive-css','https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css');
            
		break;
	}
}
add_action( 'wcfm_load_styles', 'wcfm_csm_load_styles' );
add_action( 'after_wcfm_load_styles', 'wcfm_csm_load_styles' );



add_action('wp_ajax_datatables_endpoint', 'load_template_products'); //logged in
add_action('wp_ajax_no_priv_datatables_endpoint', 'load_template_products'); //not logged in

// add_action('wp_ajax_datatables_endpoint', 'prodigi_load_templates'); //logged in
// add_action('wp_ajax_no_priv_datatables_endpoint', 'prodigi_load_templates'); //not logged in


function load_table_scripts() {
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'table-js',  $plugin_url .'../js/load-table.js', array( 'jquery' ), $WCFM->version, true );
    wp_localize_script( 'table-js', 'tableAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'load_table_scripts' );





function prodigi_load_templates(){
    global $WCFM, $wpdb, $_POST;
		
    $wcfmu_products_status = apply_filters( 'wcfmu_products_menus', array(  
                                                                                                                                        'template' => __( 'Published', 'wc-frontend-manager'),
                                                                                                                
                                                                                                                                    ) );
    
    $length = sanitize_text_field( $_POST['length'] );
    $offset = sanitize_text_field( $_POST['start'] );
    
    if( class_exists('WooCommerce_simple_auction') ) {
        remove_all_filters( 'pre_get_posts' );
    }
    
    $args = array(
                        'posts_per_page'   => -1,
                        'offset'           => $offset,
                        'category'         => '',
                        'category_name'    => '',
                        'orderby'          => 'date',
                        'order'            => 'DESC',
                        'include'          => '',
                        'exclude'          => '',
                        'meta_key'         => '',
                        'meta_value'       => '',
                        'post_type'        => 'product',
                        'post_mime_type'   => '',
                        'post_parent'      => '',
                        //'author'	   => get_current_user_id(),
                        'post_status'      => array('template' ),
                        'suppress_filters' => 0 
                    );
    $for_count_args = $args;
    
    if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) {
        $args['s'] = $_POST['search']['value'];
    }
    
    if( isset($_POST['product_status']) && !empty($_POST['product_status']) && ( $_POST['product_status'] != 'any' ) ) $args['post_status'] = sanitize_text_field( $_POST['product_status'] );
  
  if( isset($_POST['product_type']) && !empty($_POST['product_type']) ) {
        if ( 'downloadable' == $_POST['product_type'] ) {
            $args['meta_value']    = 'yes';
            $args['meta_key']      = '_downloadable';
        } elseif ( 'virtual' == $_POST['product_type'] ) {
            $args['meta_value']    = 'yes';
            $args['meta_key']      = '_virtual';
        } elseif ( 'variable' == $_POST['product_type'] || 'simple' == $_POST['product_type'] ) {
            $args['tax_query'][] = array(
                                                                    'taxonomy' => 'product_type',
                                                                    'field' => 'slug',
                                                                    'terms' => array(wc_clean($_POST['product_type'])),
                                                                    'operator' => 'IN'
                                                                );
        } else {
            $args['tax_query'][] = array(
                                                                    'taxonomy' => 'product_type',
                                                                    'field' => 'slug',
                                                                    'terms' => array(wc_clean($_POST['product_type'])),
                                                                    'operator' => 'IN'
                                                                );
        }
    }
    
    if( isset($_POST['product_cat']) && !empty($_POST['product_cat']) ) {
        $args['tax_query'][] = array(
                                                                    'taxonomy' => 'product_cat',
                                                                    'field'    => 'term_id',
                                                                    'terms'    => array(wc_clean($_POST['product_cat'])),
                                                                    'operator' => 'IN'
                                                                );
    }
    
    if( isset($_POST['product_taxonomy']) && !empty($_POST['product_taxonomy']) && is_array( $_POST['product_taxonomy'] ) ) {
        foreach( $_POST['product_taxonomy'] as $custom_taxonomy => $taxonomy_id ) {
            if( $taxonomy_id ) {
                $args['tax_query'][] = array(
                                                                            'taxonomy' => $custom_taxonomy,
                                                                            'field'    => 'term_id',
                                                                            'terms'    => array($taxonomy_id),
                                                                            'operator' => 'IN'
                                                                        );
            }
        }
    }
    
    
    // Order by SKU
    if( isset( $_POST['order'] ) && isset( $_POST['order'][0] ) && isset( $_POST['order'][0]['column'] ) && ( $_POST['order'][0]['column'] == 3 ) ) {
        $args['meta_key'] = '_sku';
        $args['orderby']  = 'meta_value';
        $args['order']    = wc_clean($_POST['order'][0]['dir']);
    }
    
    // Order by Price
    if( isset( $_POST['order'] ) && isset( $_POST['order'][0] ) && isset( $_POST['order'][0]['column'] ) && ( $_POST['order'][0]['column'] == 6 ) ) {
        $args['meta_key'] = '_price';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = wc_clean($_POST['order'][0]['dir']);
    }
    
    // Order by View Count
    if( isset( $_POST['order'] ) && isset( $_POST['order'][0] ) && isset( $_POST['order'][0]['column'] ) && ( $_POST['order'][0]['column'] == 9 ) ) {
        $args['meta_key'] = '_wcfm_product_views';
        $args['orderby']  = 'meta_value_num';
        $args['order']    = wc_clean($_POST['order'][0]['dir']);
    }
    
    // Order by Date
    if( isset( $_POST['order'] ) && isset( $_POST['order'][0] ) && isset( $_POST['order'][0]['column'] ) && ( $_POST['order'][0]['column'] == 10 ) ) {
        $args['orderby']  = 'date';
        $args['order']    = wc_clean($_POST['order'][0]['dir']);
    }
    
    // $args = apply_filters( 'wcfm_products_args', $args );
    
    $wcfm_products_array = get_posts( $args );
    
    // var_dump($wcfm_products_array);
    $pro_count = 0;
    $filtered_pro_count = 0;
    // Get Product Count
    // $current_user_id  = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    // if( !wcfm_is_vendor() ) $current_user_id = 0;
    $count_products = array();
    $pro_count = count($wcfm_products_array);
    
    // Get Filtered Post Count
    $filtered_pro_count = $pro_count; 
    
    if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) {
        
        $args['posts_per_page'] = -1;
        $args['offset'] = 0;
        $args['fields'] = 'ids';
        
        $wcfm_products_count_array = get_posts( $args );
        $filtered_pro_count = $pro_count = count( $wcfm_products_count_array );
        
        unset( $args['s'] );
        unset( $args['fields'] );
        
        $search_ids = array();
        $terms      = explode( ',', wc_clean($_POST['search']['value']) );

        foreach ( $terms as $term ) {
            if ( is_numeric( $term ) ) {
                $search_ids[] = $term;
            }

            // Attempt to get a SKU
            $sku_to_id = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_parent FROM {$wpdb->posts} LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id WHERE meta_key='_sku' AND meta_value LIKE %s;", '%' . $wpdb->esc_like( wc_clean( $term ) ) . '%' ) );
            $sku_to_id = array_merge( wp_list_pluck( $sku_to_id, 'ID' ), wp_list_pluck( $sku_to_id, 'post_parent' ) );

            if ( ( $sku_to_id != 0 ) && sizeof( $sku_to_id ) > 0 ) {
                $search_ids = array_merge( $search_ids, $sku_to_id );
            }
        }
        
        if( !empty( $search_ids ) ) {
            if( ( !is_array( $args['include'] ) && $args['include'] == '' ) || ( is_array($args['include']) && empty( $args['include'] ) ) ) {
                $args['include'] = $search_ids;
            } elseif( is_array($args['include']) && !empty( $args['include'] ) ) {
                $args['include'] = array_merge( $args['include'], $search_ids );
            }
        
            $wcfm_sku_search_products_array = get_posts( $args );
            
            if( count( $wcfm_sku_search_products_array ) > 0 ) {
                $wcfm_products_array = array_merge( $wcfm_products_array, $wcfm_sku_search_products_array );
                $wcfm_products_array = wcfm_unique_obj_list( $wcfm_products_array );
                $filtered_pro_count += count( $wcfm_products_array );
            }
        }
    }
    
    // Generate Products JSON
    $wcfm_products_json = '';
    $wcfm_products_json = '{
                                                        "draw": ' . wc_clean($_POST['draw']) . ',
                                                        "recordsTotal": ' . $pro_count . ',
                                                        "recordsFiltered": ' . $filtered_pro_count . ',
                                                        "data": ';
    if(!empty($wcfm_products_array)) {
        $index = 0;
        $wcfm_products_json_arr = array();
        foreach($wcfm_products_array as $wcfm_products_single) {
            $the_product = wc_get_product( $wcfm_products_single );
            
            if( !is_a( $the_product, 'WC_Product' ) ) continue;
            
            // Bulk Action Checkbox
            if( apply_filters( 'wcfm_is_allow_bulk_edit', true ) && WCFM_Dependencies::wcfmu_plugin_active_check() ) {
                $wcfm_products_json_arr[$index][] =  '<input type="checkbox" class="wcfm-checkbox bulk_action_checkbox_single" name="bulk_action_checkbox[]" value="' . $wcfm_products_single->ID . '" />';
            } else {
                $wcfm_products_json_arr[$index][] =  '';
            }
            
            // Thumb
            if( ( ( $wcfm_products_single->post_status != 'publish' ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $wcfm_products_single->ID ) ) || ( apply_filters( 'wcfm_is_allow_edit_products', true ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $wcfm_products_single->ID ) ) ) {
                $wcfm_products_json_arr[$index][] =  '<a href="' . get_wcfm_edit_product_url($wcfm_products_single->ID, $the_product) . '">' . $the_product->get_image( 'thumbnail' ) . '</a>';
            } else {
                $wcfm_products_json_arr[$index][] =  $the_product->get_image( 'thumbnail' );
            }
            
            // Title
            if( ( ( $wcfm_products_single->post_status != 'publish' ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $wcfm_products_single->ID ) ) || ( apply_filters( 'wcfm_is_allow_edit_products', true ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $wcfm_products_single->ID ) ) ) {
                $wcfm_products_json_arr[$index][] =  apply_filters( 'wcfm_product_title_dashboard', '<a href="' . get_wcfm_edit_product_url($wcfm_products_single->ID, $the_product) . '" class="wcfm_product_title">' . $wcfm_products_single->post_title . '</a>', $wcfm_products_single->ID );
            } else {
                $wcfm_products_json_arr[$index][] =  apply_filters( 'wcfm_product_title_dashboard', $wcfm_products_single->post_title, $wcfm_products_single->ID );
            }
            
            // SKU
            $product_sku = ( get_post_meta($wcfm_products_single->ID, '_sku', true) ) ? get_post_meta( $wcfm_products_single->ID, '_sku', true ) : '-';
            $wcfm_products_json_arr[$index][] =  apply_filters( 'wcfm_product_sku_dashboard', $product_sku, $wcfm_products_single->ID );
            
            // Status
            if( $wcfm_products_single->post_status == 'publish' ) {
                $wcfm_products_json_arr[$index][] =  '<span class="product-status product-status-' . $wcfm_products_single->post_status . '">' . __( 'Published', 'wc-frontend-manager' ) . '</span>';
            } else {
                if( isset( $wcfmu_products_status[$wcfm_products_single->post_status] ) ) {
                    $wcfm_products_json_arr[$index][] =  '<span class="product-status product-status-' . $wcfm_products_single->post_status . '">' . $wcfmu_products_status[$wcfm_products_single->post_status] . '</span>';
                } else {
                    $wcfm_products_json_arr[$index][] =  '<span class="product-status product-status-pending">' . __( ucfirst( $wcfm_products_single->post_status ), 'wc-frontend-manager' ) . '</span>';
                }
            }
            
            // Stock
            $stock_status = $the_product->get_stock_status();
            $stock_options = array('instock' => __('In stock', 'wc-frontend-manager'), 'outofstock' => __('Out of stock', 'wc-frontend-manager'), 'onbackorder' => __( 'On backorder', 'wc-frontend-manager' ) );
            if ( array_key_exists( $stock_status, $stock_options ) ) {
                $stock_html = '<span class="'.$stock_status.'">' . $stock_options[$stock_status] . '</span>';
            } else {
                $stock_html = '<span class="instock">' . __( 'In stock', 'woocommerce' ) . '</span>';
            }
    
            // If the product has children, a single stock level would be misleading as some could be -ve and some +ve, some managed/some unmanaged etc so hide stock level in this case.
            if ( $the_product->managing_stock() && ! sizeof( $the_product->get_children() ) ) {
                $stock_html .= ' (' . $the_product->get_stock_quantity() . ')';
            }
            $wcfm_products_json_arr[$index][] =  apply_filters( 'woocommerce_admin_stock_html', $stock_html, $the_product );
            
            // Price
            $wcfm_products_json_arr[$index][] =  $the_product->get_price_html() ? $the_product->get_price_html() : '<span class="na">&ndash;</span>';
            
            // Taxonomies
            $taxonomies = '';
            $pcategories = get_the_terms( $the_product->get_id(), 'product_cat' );
            if( !empty($pcategories) ) {
                $taxonomies .= '<strong>' . __( 'Categories', 'wc-frontend-manager' ) . '</strong>: ';
                $is_first = true;
                foreach($pcategories as $pkey => $pcategory) {
                    if( !$is_first ) $taxonomies .= ', ';
                    $is_first = false;
                    $taxonomies .= '<a style="color: #5B9A68" href="' . get_term_link( $pcategory->term_id ) . '" target="_blank">' . $pcategory->name . '</a>';
                }
            }
            
            // Custom Taxonomies
            if( apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
                $product_taxonomies = get_object_taxonomies( 'product', 'objects' );
                if( !empty( $product_taxonomies ) ) {
                    foreach( $product_taxonomies as $product_taxonomy ) {
                        if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
                            if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
                                // Fetching Saved Values
                                $taxonomy_values = get_the_terms( $the_product->get_id(), $product_taxonomy->name );
                                if( !empty($taxonomy_values) ) {
                                    $taxonomies .= "<br /><strong>" . __( $product_taxonomy->label, 'wc-frontend-manager' ) . '</strong>: ';
                                    $is_first = true;
                                    foreach($taxonomy_values as $pkey => $ptaxonomy) {
                                        if( !is_wp_error( $ptaxonomy ) ) {
                                            if( !$is_first ) $taxonomies .= ', ';
                                            $is_first = false;
                                            $taxonomies .= '<a style="color: #dd4b39;" href="' . get_term_link( $ptaxonomy->term_id ) . '" target="_blank">' . $ptaxonomy->name . '</a>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            if( !$taxonomies ) $taxonomies = '&ndash;';
            $wcfm_products_json_arr[$index][] =  $taxonomies;
            
            // Type
            $pro_type = '';
            if ( 'grouped' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips grouped wcicon-grouped text_tip" data-tip="' . esc_attr__( 'Grouped', 'wc-frontend-manager' ) . '"></span>';
            } if ( 'groupby' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips grouped wcicon-grouped text_tip" data-tip="' . esc_attr__( 'Group By', 'wc-frontend-manager-product-hub' ) . '"></span>';
            } elseif ( 'external' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips external wcicon-external text_tip" data-tip="' . esc_attr__( 'External/Affiliate', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'simple' == $the_product->get_type() ) {
    
                if ( $the_product->is_virtual() ) {
                    $pro_type = '<span class="product-type tips virtual wcicon-virtual text_tip" data-tip="' . esc_attr__( 'Virtual', 'wc-frontend-manager' ) . '"></span>';
                } elseif ( $the_product->is_downloadable() ) {
                    $pro_type = '<span class="product-type tips downloadable wcicon-downloadable text_tip" data-tip="' . esc_attr__( 'Downloadable', 'wc-frontend-manager' ) . '"></span>';
                } else {
                    $pro_type = '<span class="product-type tips simple wcicon-simple text_tip" data-tip="' . esc_attr__( 'Simple', 'wc-frontend-manager' ) . '"></span>';
                }
    
            } elseif ( 'variable' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips variable wcicon-variable text_tip" data-tip="' . esc_attr__( 'Variable', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'subscription' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcicon-variable text_tip" data-tip="' . esc_attr__( 'Subscription', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'variable-subscription' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcicon-variable text_tip" data-tip="' . esc_attr__( 'Variable Subscription', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'job_package' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-briefcase text_tip" data-tip="' . esc_attr__( 'Listings Package', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'resume_package' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-suitcase text_tip" data-tip="' . esc_attr__( 'Resume Package', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'auction' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-gavel text_tip" data-tip="' . esc_attr__( 'Auction', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'redq_rental' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-cab text_tip" data-tip="' . esc_attr__( 'Rental', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'booking' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-calendar text_tip" data-tip="' . esc_attr__( 'Booking', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'accommodation-booking' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-calendar text_tip" data-tip="' . esc_attr__( 'Accommodation', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'appointment' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-clock text_tip" data-tip="' . esc_attr__( 'Appointment', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'bundle' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-cubes text_tip" data-tip="' . esc_attr__( 'Bundle', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'composite' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-cubes text_tip" data-tip="' . esc_attr__( 'Composite', 'wc-frontend-manager' ) . '"></span>';
            } elseif ( 'lottery' == $the_product->get_type() ) {
                $pro_type = '<span class="product-type tips wcfmfa fa-dribbble text_tip" data-tip="' . esc_attr__( 'Lottery', 'wc-frontend-manager' ) . '"></span>';
            } else {
                // Assuming that we have other types in future
                $pro_type = '<span class="product-type tips wcicon-' . $the_product->get_type() . ' text_tip ' . $the_product->get_type() . '" data-tip="' . ucfirst( $the_product->get_type() ) . '"></span>';
            }
            $wcfm_products_json_arr[$index][] =  apply_filters( 'wcfm_products_product_type_display', $pro_type, $the_product->get_type(), $the_product );
            
            // Views
            $wcfm_products_json_arr[$index][] =  '<span class="view_count">' . (int) get_post_meta( $wcfm_products_single->ID, '_wcfm_product_views', true ) . '</span>';
            
            // Date
            $wcfm_products_json_arr[$index][] =  get_post_meta($wcfm_products_single->ID, '_printable_sku', true);
            
           
            // }
            $wcfm_products_json_arr[$index][] =  get_post_meta($wcfm_products_single->ID, 'trifie_product_min_price', true);

            // Additional Info
            $wcfm_products_json_arr[$index][] = apply_filters( 'wcfm_products_additonal_data', '&ndash;', $wcfm_products_single->ID );
            
            // Action
            $actions = '';
            $delete_nonce = wp_create_nonce('delete_template_nonce');
            
            if( apply_filters( 'wcfm_is_allow_view_product', true ) ) {
                $actions .= '<a class="wcfm-action-icon" target="_blank" href="' . apply_filters( 'wcfm_product_preview_url', get_permalink( $wcfm_products_single->ID ) ) . '"><span class="wcfmfa fa-eye text_tip" data-tip="' . esc_attr__( 'View', 'wc-frontend-manager' ) . '"></span></a>';
            }

            $actions .= '<a class="clone-template wcfm-action-icon" href="#" data-id="' . $wcfm_products_single->ID . '"><span class="wcfmfa fa-copy text_tip" data-tip="' . esc_attr__( 'Clone Template', 'wc-frontend-manager' ) . '"></span></a>';
            $actions .= ( apply_filters( 'wcfm_is_allow_delete_products', true ) && apply_filters( 'wcfm_is_allow_delete_specific_products', true, $wcfm_products_single->ID ) ) ? '<a class="wcfm-action-icon wcfm_product_delete" href="#" data-nonce="'.$delete_nonce .'" data-proid="' . $wcfm_products_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>' : '';
            $wcfm_products_json_arr[$index][] =  apply_filters ( 'wcfm_products_actions',  $actions, $the_product );
            
            
            $index++;
        }												
    }
    if( !empty($wcfm_products_json_arr) ) $wcfm_products_json .= json_encode($wcfm_products_json_arr);
    else $wcfm_products_json .= '[]';
    $wcfm_products_json .= '
                                                }';
                                                
    echo $wcfm_products_json;
    wp_die();
    
}

add_action('wp_ajax_clone_prodigi_template', 'clone_prodigi_template');
add_action('wp_ajax_no_priv_clone_prodigi_template','clone_prodigi_template');

add_action( 'wp_ajax_delete_product_template', 'delete_product_template' );
add_action( 'wp_ajax_no_priv_delete_product_template', 'delete_product_template' );



function delete_product_template(){
    // wp_delete_post( $_REQUEST['id'] );
    $permission = check_ajax_referer( 'delete_template_nonce', 'nonce', false );
    if(!$permission){
        echo 'Post not deleted, and error occured';
        wp_die();
    }else{
        $post_id = $_REQUEST['id'];
        wp_update_post(array(
            'ID'    =>  $post_id,
            'post_status'   =>  'draft'
            ));
        wp_delete_post( $post_id, true );
        echo 'Product Template '.$post_id.' Deleted Successfully';
        wp_die();
    }
}





// Clone Prodigi Template
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


function load_template_products(){
    global $wpdb;
  //some basic query arguments 
  $posts_per_page = -1;
  $args = array(
    'post_type'             => 'product',
    'post_status'       => 'template',
    'posts_per_page'        => $posts_per_page,
    'post_parent'      => '',
    'post_status'      => array('template' ),
  );
  //query
  $postsQ = new WP_Query( $args );
 
  //create empty array and loop through results, populating array
  $return_json = array();
  $index = 0;
  while($postsQ->have_posts()) {
    $url = get_the_permalink(get_the_ID());
    $postsQ->the_post();
     $actions = '<a class="wcfm-action-icon" href="#"><span class="fa fa-copy clone-template text_tip" data-id= "'.get_the_ID().'" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span></a>';
    $actions = '<a class="wcfm-action-icon " target="_blank" href="'.site_url().'/store-manager/trifie_sku-manage/'.get_the_ID().'"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit Template', 'wcfm-cpt' ) . '"></span></a>';;
    $actions .= '<a class="wcfm-action-icon " target="_blank" href="'.site_url().'/store-manager/products-manage?prodigi-id='.get_the_ID().'"><span class="fa fa-clone text_tip" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span></a>';
     $link = '<a href="'.$url.'" target="_blank">'. get_the_title().' </a>';
    // $link = get_the_title();
    $prod= wc_get_product( get_the_ID() );
    
    $prodigi_trifie_sku = get_post_meta(get_the_ID(), '_printable_sku', true);
    $sku = get_post_meta(get_the_ID(), '_sku', true);
    $trifie_sku_recomended_size= get_post_meta(get_the_ID(), 'trifie_sku_recomended_size', true);
    $trifie_product_cost =get_post_meta(get_the_ID(), '_price', true);

    $stock_status = $prod->get_stock_status();
    $st_st = '';
    if($stock_status == 'instock'){
        $st_st = 'Available';
    }else{
        $st_st = 'Un-available';
    }
    $new_stock_html = '<span class="product-'.$stock_status.'">' . $st_st . '</span>';
    $trifie_product_min_price= get_post_meta(get_the_ID(), 'trifie_product_min_price', true);

    $return_json[$index][] = $link;
    $return_json[$index][] = $sku;
    $return_json[$index][] = $new_stock_html;
    $return_json[$index][]= $trifie_product_cost;
    $return_json[$index][]= $trifie_product_min_price; 
    $return_json[$index][] = $prodigi_trifie_sku;
    // $return_json[$index][] = $trifie_sku_recomended_size;
    // $return_json[$index][]= $trifie_product_min_price;
   
    $return_json[$index][]=$actions;
    
    $index++;

    
  }
  //return the result to the ajax request and die
//   echo json_encode(array("recordsTotal"=>$postsQ->found_posts, 'data' => $return_json));
    echo json_encode($return_json);
  wp_die();

}


