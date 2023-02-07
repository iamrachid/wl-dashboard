<?php
/*
* plugin name: dashboard api
* plugin URI: /
* Description: dashboard
* Author: ensa
* Author URI:
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

if( !defined( 'WLDB_VER' ) )
    define( 'WLDB_VER', '1.0.0' );

define( 'WLDB__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WLDB__PLUGIN_RELATIVE_DIR', '/wp-content/plugins/dashboard/', false);

require_once( WLDB__PLUGIN_DIR . 'dashboard-api.php');
require_once( WLDB__PLUGIN_DIR . 'dashboard-view.php');
require_once( WLDB__PLUGIN_DIR . 'dashboard-admin.php');

// Instantiate our api
$WL_Dashboard_API = WL_Dashboard_API::getInstance();

// Instantiate our view
$WL_Dashboard_View = WL_Dashboard_View::getInstance();

// Instantiate our admin panel
$WL_Dashboard_Admin = WL_Dashboard_Admin::getInstance();
