<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Cpt1 Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/cpt1
 * @version   1.0.0
 */

class WCFM_Cpt1_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
		
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$args = array(
							'posts_per_page'   => $length,
							'offset'           => $offset,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => WCFM_CPT_1,
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => 0 
						);
		$for_count_args = $args;
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) {
			$args['s'] = $_POST['search']['value'];
		}
		
		if( isset($_POST['cpt1_status']) && !empty($_POST['cpt1_status']) ) $args['post_status'] = $_POST['cpt1_status'];
  	
		// Multi Vendor Support
		// $is_marketplace = wcfm_is_marketplace();
		// if( $is_marketplace ) {
		// 	if( isset($_POST['cpt1_vendor']) && !empty($_POST['cpt1_vendor']) ) {
		// 		if( !wcfm_is_vendor() ) {
		// 			// $args['author'] = $_POST['cpt1_vendor'];
		// 		}
		// 	}
		// 	if( wcfm_is_vendor() ) {
		// 		$args['author'] = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		// 	}
		// }
		
		$args = apply_filters( 'wcfm_cpt1_args', $args );
		
		$wcfm_cpt1_array = get_posts( $args );
		
		$cpt1_count = 0;
		$filtered_cpt1_count = 0;
		if( wcfm_is_vendor() ) {
			// Get Cpt1 Count
			$for_count_args['posts_per_page'] = -1;
			$for_count_args['offset'] = 0;
			$for_count_args = apply_filters( 'wcfm_cpt1_args', $for_count_args );
			$wcfm_cpt1_count = get_posts( $for_count_args );
			$cpt1_count = count($wcfm_cpt1_count);
			
			// Get Filtered Post Count
			$args['posts_per_page'] = -1;
			$args['offset'] = 0;
			$wcfm_filterd_cpt1_array = get_posts( $args );
			$filtered_cpt1_count = count($wcfm_filterd_cpt1_array);
		} else {
			// Get Cpt1 Count
			$wcfm_cpt1_counts = wp_count_posts('post');
			foreach($wcfm_cpt1_counts as $wcfm_cpt1_type => $wcfm_cpt1_count ) {
				if( in_array( $wcfm_cpt1_type, array( 'publish', 'draft', 'pending' ) ) ) {
					$cpt1_count += $wcfm_cpt1_count;
				}
			}
			
			// Get Filtered Post Count
			$filtered_cpt1_count = $cpt1_count; 
		}
		
		// Generate Cpt1 JSON
		$wcfm_cpt1_json = '';
		$wcfm_cpt1_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $cpt1_count . ',
															"recordsFiltered": ' . $filtered_cpt1_count . ',
															"data": ';
		if(!empty($wcfm_cpt1_array)) {
			$index = 0;
			$wcfm_cpt1_json_arr = array();
			foreach($wcfm_cpt1_array as $wcfm_cpt1_single) {

				// Get post detail	
				$prodigi_sku = get_post_meta( $wcfm_cpt1_single->ID, 'prodigi_trifie_sku', true );
				$min_price = get_post_meta( $wcfm_cpt1_single->ID, 'trifie_product_min_price', true );
				$trifie_sku_recomended_size = get_post_meta( $wcfm_cpt1_single->ID, 'trifie_sku_recomended_size', true );
				$product_cost = get_post_meta($wcfm_cpt1_single->ID, 'trifie_product_cost', true);
				$trifie_product_id = get_post_meta($wcfm_cpt1_single->ID, 'trifie_product_id', true);
				
				// Title
				if( apply_filters( 'wcfm_is_allow_edit_cpt1', true ) ) {
					// 1
					$wcfm_cpt1_json_arr[$index][] =  '<a href="' . get_wcfm_cpt1_manage_url( $wcfm_cpt1_single->ID ) . '" class="wcfm_cpt1_title wcfm_dashboard_item_title">' . $wcfm_cpt1_single->post_title . '</a>';
				} else {
					if( $wcfm_cpt1_single->post_status == 'publish' ) {
						$wcfm_cpt1_json_arr[$index][] =  apply_filters( 'wcfm_cpt1_title_dashboard', $wcfm_cpt1_single->post_title, $wcfm_cpt1_single->ID );
					} elseif( apply_filters( 'wcfm_is_allow_edit_cpt1', true ) ) {
						$wcfm_cpt1_json_arr[$index][] =  apply_filters( 'wcfm_cpt1_title_dashboard', '<a href="' . get_wcfm_cpt1_manage_url( $wcfm_cpt1_single->ID ) . '" class="wcfm_cpt1_title wcfm_dashboard_item_title">' . $wcfm_cpt1_single->post_title . '</a>', $wcfm_cpt1_single->ID );
					} else {
						$wcfm_cpt1_json_arr[$index][] =  apply_filters( 'wcfm_cpt1_title_dashboard', $wcfm_cpt1_single->post_title, $wcfm_cpt1_single->ID );
					}
				}

				// Prodigi 
				
				// 2

				$wcfm_cpt1_json_arr[$index][] =__( $prodigi_sku, 'wcfm-cpt' );

				// Recomended Size
				// 3
				$wcfm_cpt1_json_arr[$index][] =  $trifie_sku_recomended_size;

			
				
				// Taxonomies
				$taxonomies = '';
				$product_taxonomies = get_object_taxonomies( WCFM_CPT_1, 'objects' );
				if( !empty( $product_taxonomies ) ) {
					foreach( $product_taxonomies as $product_taxonomy ) {
						if( !in_array( $product_taxonomy->name, array( 'post_tag' ) ) ) {
							if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
								// Fetching Saved Values
								$taxonomy_values = get_the_terms( $wcfm_cpt1_single->ID, $product_taxonomy->name );
								if( !empty($taxonomy_values) ) {
									$taxonomies .= "<strong>" . __( $product_taxonomy->label, 'wc-frontend-manager' ) . '</strong>: ';
									$is_first = true;
									foreach($taxonomy_values as $pkey => $ptaxonomy) {
										if( !$is_first ) $taxonomies .= ', ';
										$is_first = false;
										$taxonomies .= '<a style="color: #dd4b39;" href="' . get_term_link( $ptaxonomy->term_id ) . '" target="_blank">' . $ptaxonomy->name . '</a>';
									}
								}
							}
						}
					}
				}
				
				// if( !$taxonomies ) $taxonomies = '&ndash;';
				// 4
				$wcfm_cpt1_json_arr[$index][] =  $taxonomies;
				// $wcfm_cpt1_json_arr[$index][] =  $taxonomies;

				
				// Product Cost
				// 5
				$wcfm_cpt1_json_arr[$index][] = $product_cost;

				// Minimum Price
				// 6
				$wcfm_cpt1_json_arr[$index][] =  $min_price;


				// Trifie Product ID
				// 7
				//  $wcfm_cpt1_json_arr[$index][] =  $trifie_product_id;
				


				$site_url = get_site_url();
				// Action
				// $actions = '<a class="wcfm-action-icon" target="_blank" href="' . get_permalink( $wcfm_cpt1_single->ID ) . '"><span class="fa fa-eye text_tip" data-tip="' . esc_attr__( 'View', 'wcfm-cpt' ) . '"></span></a>';
				$actions = '<a class="wcfm-action-icon " target="_blank" href="'.$site_url.'/store-manager/products-manage?prodigi-id='.$wcfm_cpt1_single->ID.'"><span class="fa fa-clone text_tip" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span></a>';
				// $actions .= '<span id="clone_product" class="wcfm-action-icon clone_product fa fa-clone text_tip" data-tip="' . esc_attr__( 'Clone Template', 'wcfm-cpt' ) . '"></span>';

				// if( $wcfm_cpt1_single->post_status == 'publish' ) {
				// 	$actions .= ( apply_filters( 'wcfm_is_allow_edit_cpt1', true ) ) ? '<a class="wcfm-action-icon" href="' . get_wcfm_cpt1_manage_url( $wcfm_cpt1_single->ID ) . '"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit', 'wcfm-cpt' ) . '"></span></a>' : '';
				// 	// $actions .= ( apply_filters( 'wcfm_is_allow_delete_cpt1', true ) ) ? '<a class="wcfm-action-icon wcfm_cpt1_delete" href="#" data-cpt1id="' . $wcfm_cpt1_single->ID . '"><span class="fa fa-trash-o text_tip" data-tip="' . esc_attr__( 'Delete', 'wcfm-cpt' ) . '"></span></a>' : '';
				// } else {
				// 	$actions .= ( apply_filters( 'wcfm_is_allow_edit_cpt1', true ) ) ? '<a class="wcfm-action-icon" href="' . get_wcfm_cpt1_manage_url( $wcfm_cpt1_single->ID ) . '"><span class="fa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit', 'wcfm-cpt' ) . '"></span></a>' : '';
				// 	// $actions .= ( apply_filters( 'wcfm_is_allow_delete_cpt1', true ) ) ? '<a class="wcfm_cpt1_delete wcfm-action-icon" href="#" data-cpt1id="' . $wcfm_cpt1_single->ID . '"><span class="fa fa-trash-o text_tip" data-tip="' . esc_attr__( 'Delete', 'wcfm-cpt' ) . '"></span></a>' : '';
				// }
				// 8
				$wcfm_cpt1_json_arr[$index][] =  apply_filters ( 'wcfm_cpt1_actions',  $actions, $wcfm_cpt1_single );
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_cpt1_json_arr) ) $wcfm_cpt1_json .= json_encode($wcfm_cpt1_json_arr);
		else $wcfm_cpt1_json .= '[]';
		$wcfm_cpt1_json .= '
													}';
													
		echo $wcfm_cpt1_json;
		
	}
}