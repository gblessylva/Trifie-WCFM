<?php

if(!defined('ABSPATH')) exit; // Exit if accessed directly

// if(!defined('WCFM_TOKEN')) return;
// if(!defined('WCFM_TEXT_DOMAIN')) return;

// if ( ! class_exists( 'WCFMcpt_Dependencies' ) )
// 	require_once 'helpers/class-wcfm-cpt-dependencies.php';

// if( !WCFMcpt_Dependencies::woocommerce_plugin_active_check() )
// 	return;

// if( !WCFMcpt_Dependencies::wcfm_plugin_active_check() )
// 	return;

require_once 'helpers/wcfm-cpt-core-functions.php';
require_once 'prodigi-cpt-config.php';

if(!class_exists('WCFM_CPT')) {
	include_once( 'core/class-wcfm-cpt.php' );
	global $WCFM, $WCFMcpt, $WCFM_Query;
	$WCFMcpt = new WCFM_CPT( __FILE__ );
	$GLOBALS['WCFMcpt'] = $WCFMcpt;
    
}
// $WCFMcpt();