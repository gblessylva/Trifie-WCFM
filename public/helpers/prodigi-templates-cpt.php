<?php

if(!function_exists('prodigi_cpt_url')) {
	function prodigi_cpt_url( $prodigi_cpt_status = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$prodigi_cpt_url = wcfm_get_endpoint_url( WCFM_CPT_1, '', $wcfm_page );
		if($prodigi_cpt_status) $prodigi_cpt_url = add_query_arg( 'prodigi_cpt_status', $prodigi_cpt_status, $prodigi_cpt_url );
		return apply_filters( 'prodigi_cpt_url', $prodigi_cpt_url );
	}
}

if(!function_exists('get_wcfm_cpt1_manage_url')) {
	function get_wcfm_cpt1_manage_url( $cpt1_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$get_wcfm_cpt1_manage_url = wcfm_get_endpoint_url( WCFM_CPT_1 . '-manage', $cpt1_id, $wcfm_page );
		return apply_filters( 'wcfm_cpt1_manage_url', $get_wcfm_cpt1_manage_url );
	}
}

if(!function_exists('get_wcfm_cpt_manager_messages')) {
	function get_wcfm_cpt_manager_messages() {
		global $WCFM;
		
		$messages = apply_filters( 'wcfm_validation_messages_cpt_manager', array(
																																								'no_title'        => __('Please insert Title before submit.', 'wcfm-cpt'),
																																								'cpt_saved'       => __('Successfully Saved.', 'wcfm-cpt'),
																																								'cpt_pending'     => __( 'Successfully submitted for moderation.', 'wcfm-cpt' ),
																																								'cpt_published'   => __('Successfully Published.', 'wcfm-cpt'),
																																								'delete_confirm'  => __( "Are you sure and want to delete this?\nYou can't undo this action ...", 'wcfm-cpt'),
																																								) );
		
		return $messages;
	}
}